<?php
/**
 * Form Controller — handles form rendering and submission.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Form_Controller {

    public function __construct() {
        add_shortcode( 'wpla_form', array( $this, 'render_shortcode' ) );
        add_action( 'wp_ajax_wpla_form_submit', array( $this, 'handle_submit' ) );
        add_action( 'wp_ajax_nopriv_wpla_form_submit', array( $this, 'handle_submit' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * Enqueue frontend form assets.
     */
    public function enqueue_assets(): void {
        wp_register_style( 'wpla-forms', WPLA_PLUGIN_URL . 'assets/css/forms.css', array(), WPLA_VERSION );
        wp_register_script( 'wpla-forms', WPLA_PLUGIN_URL . 'assets/js/forms.js', array(), WPLA_VERSION, true );
        wp_localize_script( 'wpla-forms', 'wpla_form_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wpla_form_nonce' ),
        ) );
    }

    /**
     * Render [wpla_form list="ID"] shortcode.
     */
    public function render_shortcode( $atts ): string {
        $atts = shortcode_atts( array(
            'list'        => 0,
            'title'       => '',
            'button_text' => __( 'Subscribe', 'lc-crm' ),
            'class'       => '',
        ), $atts, 'wpla_form' );

        $list_id = absint( $atts['list'] );
        $list    = $list_id ? WPLA_List_Model::get( $list_id ) : null;

        // Default form fields.
        $fields = array(
            array( 'name' => 'email', 'label' => __( 'Email', 'lc-crm' ), 'type' => 'email', 'required' => true ),
            array( 'name' => 'first_name', 'label' => __( 'First Name', 'lc-crm' ), 'type' => 'text', 'required' => false ),
            array( 'name' => 'last_name', 'label' => __( 'Last Name', 'lc-crm' ), 'type' => 'text', 'required' => false ),
            array( 'name' => 'phone', 'label' => __( 'Phone', 'lc-crm' ), 'type' => 'tel', 'required' => false ),
        );

        if ( $list && ! empty( $list->form_fields ) ) {
            $custom = json_decode( $list->form_fields, true );
            if ( is_array( $custom ) && ! empty( $custom ) ) {
                $fields = $custom;
            }
        }

        wp_enqueue_style( 'wpla-forms' );
        wp_enqueue_script( 'wpla-forms' );

        $extra_class = sanitize_html_class( $atts['class'] );
        $title       = esc_html( $atts['title'] ?: ( $list ? $list->name : '' ) );

        ob_start();
        ?>
        <div class="wpla-form-wrapper <?php echo $extra_class; ?>">
            <?php if ( $title ) : ?>
                <h3 class="wpla-form-title"><?php echo $title; ?></h3>
            <?php endif; ?>
            <form class="wpla-form" data-list="<?php echo esc_attr( $list_id ); ?>">
                <?php foreach ( $fields as $field ) :
                    $fname    = sanitize_key( $field['name'] );
                    $flabel   = esc_html( $field['label'] );
                    $ftype    = esc_attr( $field['type'] ?? 'text' );
                    $freq     = ! empty( $field['required'] );
                    ?>
                    <div class="wpla-field">
                        <label for="wpla-<?php echo $fname; ?>"><?php echo $flabel; ?></label>
                        <input
                            type="<?php echo $ftype; ?>"
                            id="wpla-<?php echo $fname; ?>"
                            name="<?php echo $fname; ?>"
                            <?php echo $freq ? 'required' : ''; ?>
                            placeholder="<?php echo $flabel; ?>"
                        />
                    </div>
                <?php endforeach; ?>

                <!-- Hidden UTM fields -->
                <input type="hidden" name="utm_source" class="wpla-utm" />
                <input type="hidden" name="utm_medium" class="wpla-utm" />
                <input type="hidden" name="utm_campaign" class="wpla-utm" />
                <input type="hidden" name="utm_content" class="wpla-utm" />
                <input type="hidden" name="utm_term" class="wpla-utm" />
                <input type="hidden" name="list_id" value="<?php echo esc_attr( $list_id ); ?>" />

                <button type="submit" class="wpla-submit">
                    <?php echo esc_html( $atts['button_text'] ); ?>
                </button>
                <div class="wpla-form-message" style="display:none;"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle AJAX form submission.
     */
    public function handle_submit(): void {
        // Verify nonce.
        if ( ! check_ajax_referer( 'wpla_form_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'lc-crm' ) ), 403 );
        }

        // Rate limiting.
        $ip    = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
        $trans = 'wpla_rate_' . md5( $ip );
        $count = (int) get_transient( $trans );
        if ( $count >= 10 ) {
            wp_send_json_error( array( 'message' => __( 'Too many submissions. Please try again later.', 'lc-crm' ) ), 429 );
        }
        set_transient( $trans, $count + 1, 60 );

        $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        if ( ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid email.', 'lc-crm' ) ) );
        }

        $data = array(
            'email'        => $email,
            'first_name'   => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
            'last_name'    => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
            'phone'        => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
            'source'       => 'form',
            'utm_source'   => isset( $_POST['utm_source'] ) ? sanitize_text_field( wp_unslash( $_POST['utm_source'] ) ) : '',
            'utm_medium'   => isset( $_POST['utm_medium'] ) ? sanitize_text_field( wp_unslash( $_POST['utm_medium'] ) ) : '',
            'utm_campaign' => isset( $_POST['utm_campaign'] ) ? sanitize_text_field( wp_unslash( $_POST['utm_campaign'] ) ) : '',
            'utm_content'  => isset( $_POST['utm_content'] ) ? sanitize_text_field( wp_unslash( $_POST['utm_content'] ) ) : '',
            'utm_term'     => isset( $_POST['utm_term'] ) ? sanitize_text_field( wp_unslash( $_POST['utm_term'] ) ) : '',
            'ip_address'   => $ip,
        );

        $result = WPLA_Contact::create( $data );

        if ( is_wp_error( $result ) ) {
            // If duplicate, get existing contact.
            if ( 'duplicate_email' === $result->get_error_code() ) {
                $err_data   = $result->get_error_data();
                $contact_id = $err_data['contact_id'] ?? 0;
                if ( $contact_id ) {
                    WPLA_Contact::update( $contact_id, $data );
                }
            } else {
                wp_send_json_error( array( 'message' => $result->get_error_message() ) );
            }
        } else {
            $contact_id = $result;
        }

        // Subscribe to list.
        $list_id = isset( $_POST['list_id'] ) ? absint( $_POST['list_id'] ) : 0;
        if ( $list_id && ! empty( $contact_id ) ) {
            WPLA_Contact::subscribe_list( (int) $contact_id, $list_id );
        }

        // Fire form_submitted event.
        do_action( 'wpla_event', 'form_submitted', (int) $contact_id, array(
            'list_id' => $list_id,
            'source'  => 'form',
        ) );

        wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'lc-crm' ) ) );
    }
}
