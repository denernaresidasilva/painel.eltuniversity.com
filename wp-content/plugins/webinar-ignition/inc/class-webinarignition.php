<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * WebinarIgnition setup
 *
 * @package WebinarIgnition
 * @since   1.9.187
 */
/**
 * Main WebinarIgnition Class.
 *
 * @class WebinarIgnition
 */
final class WebinarIgnition {
    /**
     * WebinarIgnition version.
     *
     * @var string
     */
    public $version = WEBINARIGNITION_VERSION;

    /**
     * WebinarIgnition version.
     *
     * @var string
     */
    public static $plugin_basename = null;

    /**
     * The single instance of the class.
     *
     * @var WebinarIgnition
     */
    protected static $instance = null;

    /**
     * Main WebinarIgnition Instance.
     *
     * Ensures only one instance of WebinarIgnition is loaded or can be loaded.
     *
     * @return WebinarIgnition - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * WebinarIgnition Constructor.
     */
    public function __construct() {
        self::$plugin_basename = self::webinarignition_get_plugin_basename();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'webinarignition_activate', 'webinarignition_installer' );
        add_filter( 'plugin_action_links_' . WEBINARIGNITION_PLUGIN_BASENAME, array($this, 'webinarignition_add_get_started_link') );
        add_filter( 'safe_style_css', function ( $styles ) {
            $styles[] = 'display';
            return $styles;
        } );
        add_action( 'wp_loaded', array($this, 'webinaringition_load_text_domain') );
        add_action( 'admin_init', array($this, 'webinarignition_redirect_after_installation') );
        add_action( 'plugins_loaded', array($this, 'webinarignition_load_plugin_textdomain') );
        add_action( 'init', array($this, 'init') );
        add_action( 'init', array($this, 'webinarignition_sign_in_support_staff') );
        add_filter(
            'sac_logged_username',
            array('WebinarignitionIntegration', 'webinarignition_set_sac_logged_username'),
            999,
            2
        );
        add_action( 'admin_init', array($this, 'webinarignition_activate_branding') );
        add_filter(
            'plugin_row_meta',
            array($this, 'webinarignition_plugin_row_meta'),
            100,
            2
        );
        webinarignition_fs()->add_filter( 'after_skip_url', array($this, 'fs_after_connect_skip_url_cb') );
        webinarignition_fs()->add_filter( 'after_connect_url', array($this, 'fs_after_connect_skip_url_cb') );
        add_action(
            'save_post_page',
            array($this, 'webinarignition_update_webinarignition_data'),
            100,
            2
        );
        // Register WI post type.
        add_action( 'init', array($this, 'webinarignition_register_post_types') );
        add_action(
            'webinarignition_campaign_created',
            array($this, 'webinarignition_create_post_for_campaign'),
            10,
            1
        );
        // Save data in post type.
        add_action(
            'added_option',
            array($this, 'webinarignition_save_option_data_in_post_meta'),
            100,
            2
        );
        // Save data in post type.
        add_action(
            'updated_option',
            array($this, 'webinarignition_save_option_data_in_post_meta'),
            100,
            2
        );
        // Add cron job to convert the data.
        add_action( 'init', array($this, 'webinarignition_schedule_cron_job'), 100 );
        add_filter(
            'cron_schedules',
            array($this, 'webinarignition_add_hourly'),
            100,
            1
        );
        add_action( 'wi_cron_convert_data', array($this, 'webinarignition_convert_data'), 100 );
    }

    public function webinarignition_add_get_started_link( $links ) {
        $url = admin_url( 'admin.php?page=webinarignition-dashboard' );
        // Replace with your desired URL
        $get_started_link = sprintf( '<a style="font-weight:bold; color:#e64f1d;" href="%s">%s</a>', esc_url( $url ), __( 'Get Started', 'webinar-ignition' ) );
        // Insert the link at the beginning of the array
        array_unshift( $links, $get_started_link );
        return $links;
    }

    public function webinaringition_load_text_domain() {
        if ( function_exists( 'load_plugin_textdomain' ) ) {
            load_plugin_textdomain( 'webinar-ignition', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }
    }

    public function webinarignition_add_hourly( $schedules ) {
        if ( !in_array( 'every_minute', array_keys( $schedules ), true ) ) {
            $schedules['every_minute'] = array(
                'interval' => 60,
                'display'  => __( 'Every Minute', 'webinar-ignition' ),
            );
        }
        return $schedules;
    }

    public function webinarignition_schedule_cron_job() {
        if ( !wp_next_scheduled( 'wi_cron_convert_data' ) ) {
            if ( 'completed' !== get_option( 'wi_data_conversion_status' ) ) {
                wp_schedule_event( time() + 5, 'every_minute', 'wi_cron_convert_data' );
            }
        } elseif ( 'completed' === get_option( 'wi_data_conversion_status' ) ) {
            wp_unschedule_event( time() + 5, 'wi_cron_convert_data' );
        }
    }

    public function webinarignition_convert_data() {
        global $wpdb;
        if ( 'no' === get_option( 'wi_update_once', 'no' ) ) {
            update_option( 'wi_update_once', 'yes' );
            update_option( 'wi_data_conversion_status', 'start' );
            update_option( 'wi_data_conversion_page', 0 );
            update_option( 'wi_converted_webinars', array() );
        }
        $page_number = get_option( 'wi_data_conversion_page', 0 );
        $status = get_option( 'wi_data_conversion_status', 'start' );
        if ( 'completed' === $status ) {
            return;
        }
        ++$page_number;
        $start_index = ($page_number - 1) * 10;
        $records = 10;
        $query = "SELECT * FROM {$wpdb->prefix}webinarignition as WIA WHERE 1=1 LIMIT {$start_index}, {$records}";
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}webinarignition as WIA WHERE 1=1 LIMIT %d, %d", $start_index, $records ) );
        if ( empty( $results ) ) {
            update_option( 'wi_data_conversion_status', 'completed' );
            return;
        }
        $total_records = count( $results );
        $converted_webinars = (array) get_option( 'wi_converted_webinars' );
        $converted_webinars_to_posts = array();
        foreach ( $results as $webinar ) {
            $id = $webinar->ID;
            if ( in_array( $id, (array) $converted_webinars, true ) ) {
                continue;
            }
            $title = $webinar->appname;
            $camtype = $webinar->camtype;
            $page_id = $webinar->postID;
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar->ID );
            $date_created = gmdate( 'Y-m-d', strtotime( $webinar->created ) );
            $total_lp = $webinar->total_lp;
            $total_ty = $webinar->total_ty;
            $total_live = $webinar->total_live;
            $total_replay = $webinar->total_replay;
            $meta_array = array();
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar->ID );
            foreach ( (array) $webinar_data as $data_key => $data ) {
                $meta_key = 'wi_' . $data_key;
                $meta_array[$meta_key] = $data;
            }
            $post_id = wp_insert_post( array(
                'post_type'    => 'wi_webinar',
                'post_status'  => 'publish',
                'post_content' => 'Automatically created by WI on Conversion.',
                'post_title'   => $title,
                'date_created' => $date_created,
                'meta_input'   => $meta_array,
            ) );
            if ( $post_id && !is_wp_error( $post_id ) ) {
                update_option( 'wi_webinar_post_id_' . $id, $post_id );
            } else {
                continue;
            }
            $converted_webinars_to_posts[] = $webinar->ID;
        }
        //end foreach
        update_option( 'wi_data_conversion_page', $page_number );
        update_option( 'wi_data_conversion_status', 'processing' );
        update_option( 'wi_converted_webinars', array_filter( array_unique( array_merge( $converted_webinars, $converted_webinars_to_posts ) ) ) );
    }

    public function webinarignition_register_post_types() {
        $labels = array(
            'name'                => esc_html__( 'Webinars', 'webinar-ignition' ),
            'singular_name'       => esc_html__( 'Webinar', 'webinar-ignition' ),
            'add_new'             => esc_html__( 'Add New Webinar', 'webinar-ignition' ),
            'add_new_item'        => esc_html__( 'Add New Webinar', 'webinar-ignition' ),
            'edit_item'           => esc_html__( 'Edit Webinar', 'webinar-ignition' ),
            'new_item'            => esc_html__( 'New Webinar', 'webinar-ignition' ),
            'view_item'           => esc_html__( 'View Webinar', 'webinar-ignition' ),
            'search_items'        => esc_html__( 'Search Webinar', 'webinar-ignition' ),
            'exclude_from_search' => true,
            'not_found'           => esc_html__( 'No Webinar found', 'webinar-ignition' ),
            'not_found_in_trash'  => esc_html__( 'No Webinar found in trash', 'webinar-ignition' ),
            'parent_item_colon'   => '',
            'all_items'           => esc_html__( 'WebinarIgnition', 'webinar-ignition' ),
            'menu_name'           => esc_html__( 'WebinarIgnition', 'webinar-ignition' ),
            'attributes'          => esc_html__( 'Webinar Priority', 'webinar-ignition' ),
            'item_published'      => esc_html__( 'Webinar published', 'webinar-ignition' ),
            'item_updated'        => esc_html__( 'Webinar updated', 'webinar-ignition' ),
        );
        $show_in_menu = ( defined( 'WI_WEBINAR_DATA_POST' ) && WI_WEBINAR_DATA_POST ? true : false );
        $args = array(
            'labels'             => $labels,
            'menu_icon'          => 'dashicons-format-video',
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => $show_in_menu,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 30,
            'rewrite'            => array(
                'slug'       => 'wi_webinar',
                'with_front' => false,
            ),
            'supports'           => array('title', 'page-attributes'),
        );
        register_post_type( 'wi_webinar', $args );
    }

    public function webinarignition_save_option_data_in_post_meta( $option_name = '', $option_value = '' ) {
        if ( false !== strpos( $option_name, 'webinarignition_campaign_' ) ) {
            $campaign_id = str_replace( 'webinarignition_campaign_', '', $option_name );
            if ( empty( intval( $campaign_id ) ) ) {
                return;
            }
            $webinar_post_id = get_option( 'wi_webinar_post_id_' . $campaign_id );
            if ( empty( $webinar_post_id ) || empty( get_post( $webinar_post_id ) ) ) {
                $webinar_post_id = $this->webinarignition_create_post_for_campaign( $campaign_id );
            }
            if ( empty( $option_value ) ) {
                $option_value = get_option( $option_name );
            }
            if ( !empty( $option_value ) ) {
                foreach ( $option_value as $index => $value ) {
                    update_post_meta( $webinar_post_id, 'wi_' . $index, $value );
                }
            }
        }
        //end if
    }

    public function webinarignition_create_post_for_campaign( $campaign_id, $args = array() ) {
        $webinar = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $campaign_id, 'object' );
        $id = $webinar->ID;
        $title = $webinar->appname;
        $camtype = $webinar->camtype;
        $page_id = $webinar->postID;
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar->ID );
        $date_created = gmdate( 'Y-m-d', strtotime( $webinar->created ) );
        $total_lp = $webinar->total_lp;
        $total_ty = $webinar->total_ty;
        $total_live = $webinar->total_live;
        $total_replay = $webinar->total_replay;
        $post_id = wp_insert_post( array(
            'post_type'    => 'wi_webinar',
            'post_status'  => 'publish',
            'post_content' => 'Enter description here.',
            'post_title'   => $title,
            'date_created' => $date_created,
        ) );
        if ( $post_id && !is_wp_error( $post_id ) ) {
            update_option( 'wi_webinar_post_id_' . $id, $post_id );
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar->ID );
            foreach ( (array) $webinar_data as $data_key => $data ) {
                $meta_key = 'wi_' . $data_key;
                update_post_meta( $post_id, $meta_key, $data );
            }
            return $post_id;
        }
    }

    public function webinarignition_update_webinarignition_data( $post_id, $post ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'webinarignition';
        $webinars = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE postID = %d", $post_id ) );
        if ( empty( $webinars ) ) {
            return;
        }
        $permalink = get_permalink( $post_id );
        foreach ( $webinars as $webinar ) {
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar->ID );
            if ( $permalink !== $webinar_data->webinar_permalink ) {
                $webinar_data->webinar_permalink = $permalink;
                update_option( 'webinarignition_campaign_' . $webinar->ID, $webinar_data );
                if ( defined( 'WI_WEBINAR_DATA_POST' ) && WI_WEBINAR_DATA_POST ) {
                    $meta_key = 'wi_webinar_permalink';
                    update_post_meta( $post_id, $meta_key, $permalink );
                }
            }
        }
        $wpdb->get_results( $wpdb->prepare( "UPDATE {$table_name} SET appname = %s WHERE postID = %d", $post->post_title, $post_id ) );
    }

    public function webinarignition_has_webinars_before_date( $date_before ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'webinarignition';
        return !empty( $wpdb->get_var( $wpdb->prepare( "SELECT W.ID FROM {$table_name} AS W WHERE STR_TO_DATE(W.created , '%%M %%d, %%Y') <= %s;", $date_before ) ) );
    }

    public function webinarignition_display_registration_num_message() {
    }

    /**
     * Return to user WI dashboard after connect/skip Freemius opt-in
     *
     * @param string $url The url.
     *
     * @return mixed|string
     */
    public static function fs_after_connect_skip_url_cb( $url ) {
        if ( 1 !== absint( get_option( 'webinarignition_activated', 0 ) ) ) {
            $url = add_query_arg( 'page', 'webinarignition-dashboard', admin_url( 'admin.php' ) );
        }
        return $url;
    }

    public static function webinarignition_get_plugin_basename() {
        return plugin_basename( WEBINARIGNITION_PLUGIN_FILE );
    }

    public function webinarignition_restrict__auto_plugin_update( $value ) {
        if ( isset( $value[self::$plugin_basename] ) ) {
            unset($value[self::$plugin_basename]);
        }
        return $value;
    }

    public static function print_pre( $data ) {
        // @developer time saving function
        // this is function can be used to display array or object easily in a well readable format
        echo "<pre>";
        print_r( $data );
        echo "</pre>";
    }

    public function webinarignition_restrict_plugin_update( $value ) {
        global $pagenow;
        if ( 'update-core.php' !== $pagenow ) {
            return $value;
        }
        $site_url = get_site_url();
        $statusCheck = new stdClass();
        $statusCheck->switch = 'free';
        $statusCheck->slug = 'free';
        $statusCheck->licensor = '';
        $statusCheck->is_free = 1;
        $statusCheck->is_dev = '';
        $statusCheck->is_registered = '';
        $statusCheck->title = 'Free';
        $statusCheck->member_area = '';
        $statusCheck->is_pending_activation = 1;
        $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
        $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
        $statusCheck->name = '';
        if ( isset( $value->response[self::$plugin_basename] ) ) {
            unset($value->response[self::$plugin_basename]);
        }
        return $value;
    }

    public function webinarignition_plugin_row_meta( $row_meta, $plugin_file ) {
        if ( self::$plugin_basename !== $plugin_file ) {
            return $row_meta;
        }
        $site_url = get_site_url();
        $statusCheck = new stdClass();
        $statusCheck->switch = 'free';
        $statusCheck->slug = 'free';
        $statusCheck->licensor = '';
        $statusCheck->is_free = 1;
        $statusCheck->is_dev = '';
        $statusCheck->is_registered = '';
        $statusCheck->title = 'Free';
        $statusCheck->member_area = '';
        $statusCheck->is_pending_activation = 1;
        $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
        $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
        $statusCheck->name = '';
        ob_start();
        ?>
		<a href="<?php 
        echo esc_url( sprintf( 'https://downloads.wordpress.org/plugin/webinar-ignition.%s.zip', esc_html( WEBINARIGNITION_PREVIOUS_VERSION ) ) );
        ?>">
		<?php 
        esc_html_e( 'Rollback to previous version.', 'webinar-ignition' );
        ?>
		</a>
		<?php 
        $row_meta['rollback'] = ob_get_clean();
        return $row_meta;
    }

    public function webinarignition_activate_branding() {
        $site_url = get_site_url();
        $statusCheck = new stdClass();
        $statusCheck->switch = 'free';
        $statusCheck->slug = 'free';
        $statusCheck->licensor = '';
        $statusCheck->is_free = 1;
        $statusCheck->is_dev = '';
        $statusCheck->is_registered = '';
        $statusCheck->title = 'Free';
        $statusCheck->member_area = '';
        $statusCheck->is_pending_activation = 1;
        $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
        $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
        $statusCheck->name = '';
        if ( isset( $_GET['action'] ) && 'toggle_branding' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {
            update_option( 'webinarignition_branding_copy', 'Webinar powered by WebinarIgnition' );
            if ( get_option( 'webinarignition_show_footer_branding', false ) ) {
                update_option( 'webinarignition_show_footer_branding', false );
            } else {
                update_option( 'webinarignition_show_footer_branding', true );
                $fg_color = get_option( 'webinarignition_footer_text_color', false );
                $bg_color = get_option( 'webinarignition_branding_background_color', false );
                if ( $bg_color == $fg_color ) {
                    update_option( 'webinarignition_footer_text_color', '#ffffff' );
                    update_option( 'webinarignition_branding_background_color', '#00000' );
                }
            }
            if ( !$statusCheck->is_registered ) {
                $reconnect_url = webinarignition_fs()->get_activation_url( array(
                    'nonce'     => wp_create_nonce( webinarignition_fs()->get_unique_affix() . '_reconnect' ),
                    'fs_action' => webinarignition_fs()->get_unique_affix() . '_reconnect',
                ) );
                wp_safe_redirect( $reconnect_url );
                exit;
            } else {
                wp_safe_redirect( admin_url( 'admin.php?page=webinarignition-dashboard' ) );
                exit;
            }
        }
        //end if
    }

    public function check_backup( $data ) {
    }

    public function webinarignition_auto_update_file( $update, $item ) {
        $site_url = get_site_url();
        $statusCheck = new stdClass();
        $statusCheck->switch = 'free';
        $statusCheck->slug = 'free';
        $statusCheck->licensor = '';
        $statusCheck->is_free = 1;
        $statusCheck->is_dev = '';
        $statusCheck->is_registered = '';
        $statusCheck->title = 'Free';
        $statusCheck->member_area = '';
        $statusCheck->is_pending_activation = 1;
        $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
        $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
        $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
        $statusCheck->name = '';
        return $update;
    }

    public function webinarignition_load_plugin_textdomain() {
        add_filter( 'plugin_locale', array($this, 'webinarignition_check_de_locale') );
        load_plugin_textdomain( 'webinarignition', false, plugin_basename( dirname( WEBINARIGNITION_PLUGIN_FILE ) . '/languages' ) );
    }

    public function webinarignition_check_de_locale( $domain ) {
        $site_lang = get_user_locale();
        $de_lang_list = array(
            'de_CH_informal',
            'de_DE_formal',
            'de_AT',
            'de_CH',
            'de_DE'
        );
        if ( in_array( $site_lang, $de_lang_list ) ) {
            return 'de_DE';
        }
        return $domain;
    }

    public function webinarignition_redirect_after_installation() {
        if ( is_user_logged_in() && intval( get_option( 'wi_redirect_after_installation' ) ) === wp_get_current_user()->ID ) {
            delete_option( 'wi_redirect_after_installation' );
            add_option( 'wi_first_install', wp_get_current_user()->ID );
            wp_safe_redirect( get_admin_url() . 'admin.php?page=webinarignition-dashboard' );
            exit;
        }
    }

    public function webinarignition_sign_in_support_staff() {
        if ( !isset( $_GET['console'] ) || empty( $_GET['_wi_host_token'] ) && empty( $_GET['_wi_support_token'] ) ) {
            return;
        }
        $request_uri = ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' );
        $postID = url_to_postid( $request_uri );
        if ( empty( $postID ) ) {
            return;
        }
        $webinar_id = absint( get_post_meta( $postID, 'webinarignitionx_meta_box_select', true ) );
        // Check if webinar page
        if ( empty( $webinar_id ) ) {
            return;
        }
        $webinar_data = get_option( 'webinarignition_campaign_' . $webinar_id );
        if ( empty( $webinar_data ) ) {
            return;
        }
        if ( !empty( $_GET['_wi_support_token'] ) ) {
            if ( !WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data ) || empty( $webinar_data->support_staff_count ) ) {
                return;
            }
            $wtlwp_token = sanitize_key( $_GET['_wi_support_token'] );
            // Input var okay.
            $user_query = new WP_User_Query(array(
                'meta_key'   => '_wi_support_token',
                'meta_value' => $wtlwp_token,
            ));
        }
        if ( !empty( $_GET['_wi_host_token'] ) ) {
            if ( !WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data, 'host' ) || empty( $webinar_data->host_member_count ) ) {
                return;
            }
            $wtlwp_token = sanitize_key( $_GET['_wi_host_token'] );
            // Input var okay.
            $user_query = new WP_User_Query(array(
                'meta_key'   => '_wi_host_token',
                'meta_value' => $wtlwp_token,
            ));
        }
        if ( empty( $user_query ) ) {
            return;
        }
        $users = $user_query->get_results();
        if ( empty( $users ) ) {
            return;
        }
        $support_link = $webinar_data->webinar_permalink . '?console';
        wp_safe_redirect( $support_link );
        exit;
    }

    /**
     * @param int    $id The lead id.
     * @param string $table The table name.
     * TODO - Move it to another place later.
     */
    public function webinarignition_lead_created( $id, $table ) {
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function webinarignition_is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'frontend':
                return !is_admin() && !defined( 'DOING_CRON' );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        include_once WEBINARIGNITION_PATH . 'vendor/autoload.php';
        require_once WEBINARIGNITION_PATH . 'inc/class-webinar-user-role-manager.php';
        require_once WEBINARIGNITION_PATH . 'inc/class-assets-manager.php';
        include_once WEBINARIGNITION_PATH . 'inc/class-webinar-ignition-helper.php';
        include_once WEBINARIGNITION_PATH . 'inc/wi-formatting-functions.php';
        include_once WEBINARIGNITION_PATH . 'inc/WebinarIgnition_Logs.php';
        include_once WEBINARIGNITION_PATH . 'inc/wi-admin-functions.php';
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionManager.php';
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionEmailManager.php';
        // migrations
        include_once WEBINARIGNITION_PATH . 'inc/migrations.php';
        // leads
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionLeadsManager.php';
        // updates
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionUpdates.php';
        // Ajax
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionAjax.php';
        // Ajax
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionQA.php';
        // Ajax
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionPowerups.php';
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionPowerupsShortcodes.php';
        // Third party plugins integration
        include_once WEBINARIGNITION_PATH . 'inc/class.WebinarignitionIntegration.php';
        // Functions
        include_once WEBINARIGNITION_PATH . 'inc/Functions/DateTimeFunctions.php';
        include_once WEBINARIGNITION_PATH . 'inc/Functions/WebinarFunctions.php';
        include_once WEBINARIGNITION_PATH . 'inc/Functions/LeadFunctions.php';
        include_once WEBINARIGNITION_PATH . 'inc/Helpers/DateHelpers.php';
        include_once WEBINARIGNITION_PATH . 'inc/Functions/extra_functions.php';
        // AJAX Callbacks:
        include_once WEBINARIGNITION_PATH . 'inc/callback.php';
        include_once WEBINARIGNITION_PATH . 'inc/callback2.php';
        include_once WEBINARIGNITION_PATH . 'inc/callback3.php';
        // Email service integration
        include_once WEBINARIGNITION_PATH . 'inc/email_service_integration.php';
        include_once WEBINARIGNITION_PATH . 'inc/autowebinar_get_dates.php';
        // Image Uploader:
        include_once WEBINARIGNITION_PATH . 'inc/image.php';
        // Menu Here:
        include_once WEBINARIGNITION_PATH . 'inc/menu.php';
        // Dashboard:
        include_once WEBINARIGNITION_PATH . 'UI/index.php';
        // Page Link:
        include_once WEBINARIGNITION_PATH . 'inc/page_link.php';
        // NEW :: Shortcode Widget
        include_once WEBINARIGNITION_PATH . 'inc/shortcode_widget.php';
        include_once WEBINARIGNITION_PATH . 'inc/wi-frontend-templates-functions.php';
        include_once WEBINARIGNITION_PATH . 'inc/wi-general-functions.php';
        if ( $this->webinarignition_is_request( 'frontend' ) ) {
            $this->frontend_includes();
        }
        include_once WEBINARIGNITION_PATH . 'inc/class-wi-emails.php';
    }

    /**
     * Include required frontend files.
     */
    public function frontend_includes() {
        include_once WEBINARIGNITION_PATH . 'inc/wi-frontend-functions.php';
    }

    /**
     * Function used to Init WebinarIgnition Template Functions - This makes them pluggable by plugins and themes.
     */
    public function include_template_functions() {
        // include_once WEBINARIGNITION_PATH . 'inc/wi-template-functions.php';
    }

    /**
     * Init WebinarIgnition when WordPress Initialises.
     */
    public function init() {
        WebinarignitionUpdates::webinarignition_check_updates();
        // Init action.
        // do_action( 'webinarignition_init' );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return apply_filters( 'webinarignition_template_path', 'webinarignition/' );
    }

    /**
     * Get Ajax URL.
     *
     * @return string
     */
    public function ajax_url() {
        return admin_url( 'admin-ajax.php', 'relative' );
    }

}
