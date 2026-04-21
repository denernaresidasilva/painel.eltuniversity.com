<?php
/**
 * Plugin Name: Leads SaaS
 * Plugin URI:  https://eltuniversity.com
 * Description: Advanced Leads + Marketing Automation + Email system for WordPress. Manage leads, automations, email templates, and engagement tracking.
 * Version:     1.0.0
 * Author:      Dener Naresi
 * Author URI:  https://denernaresi.com
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: plugin-leads-saas
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PLS_VERSION', '1.0.0' );
define( 'PLS_PLUGIN_FILE', __FILE__ );
define( 'PLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PLS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PLS_DB_VERSION', '1.0.0' );

final class Leads_SaaS {

    private static $instance = null;
    private $admin = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->register_hooks();
    }

    private function load_dependencies(): void {
        require_once PLS_PLUGIN_DIR . 'includes/class-pls-database.php';

        // Models
        require_once PLS_PLUGIN_DIR . 'includes/Models/class-pls-contact.php';
        require_once PLS_PLUGIN_DIR . 'includes/Models/class-pls-list.php';
        require_once PLS_PLUGIN_DIR . 'includes/Models/class-pls-tag.php';
        require_once PLS_PLUGIN_DIR . 'includes/Models/class-pls-email-template.php';
        require_once PLS_PLUGIN_DIR . 'includes/Models/class-pls-webinar.php';

        // Services
        require_once PLS_PLUGIN_DIR . 'includes/Services/class-pls-event-manager.php';
        require_once PLS_PLUGIN_DIR . 'includes/Services/class-pls-automation-engine.php';
        require_once PLS_PLUGIN_DIR . 'includes/Services/class-pls-message-queue.php';
        require_once PLS_PLUGIN_DIR . 'includes/Services/class-pls-lead-scoring.php';

        // Integrations
        require_once PLS_PLUGIN_DIR . 'includes/Integrations/class-pls-whatsapp.php';
        require_once PLS_PLUGIN_DIR . 'includes/Integrations/class-pls-email.php';

        // Controllers
        require_once PLS_PLUGIN_DIR . 'includes/Controllers/class-pls-form-controller.php';

        // REST API
        require_once PLS_PLUGIN_DIR . 'includes/Api/class-pls-rest-api.php';

        // Admin
        require_once PLS_PLUGIN_DIR . 'admin/class-pls-admin.php';
        if ( is_admin() ) {
            $this->admin = new PLS_Admin();
        }

        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
    }

    private function register_hooks(): void {
        register_activation_hook( PLS_PLUGIN_FILE, array( 'PLS_Database', 'install' ) );
        register_deactivation_hook( PLS_PLUGIN_FILE, array( $this, 'deactivate' ) );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

        add_action( 'pls_process_queue', array( 'PLS_Message_Queue', 'process' ) );
        add_action( 'pls_process_automations', array( 'PLS_Automation_Engine', 'process_delayed' ) );

        if ( ! wp_next_scheduled( 'pls_process_queue' ) ) {
            wp_schedule_event( time(), 'every_minute', 'pls_process_queue' );
        }
        if ( ! wp_next_scheduled( 'pls_process_automations' ) ) {
            wp_schedule_event( time(), 'every_minute', 'pls_process_automations' );
        }

        add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
    }

    public function add_cron_interval( array $schedules ): array {
        if ( ! isset( $schedules['every_minute'] ) ) {
            $schedules['every_minute'] = array(
                'interval' => 60,
                'display'  => __( 'A Cada Minuto', 'plugin-leads-saas' ),
            );
        }
        return $schedules;
    }

    public function init(): void {
        load_plugin_textdomain( 'plugin-leads-saas', false, dirname( PLS_PLUGIN_BASENAME ) . '/languages' );
        PLS_Event_Manager::instance();

        if ( get_option( 'pls_db_version' ) !== PLS_DB_VERSION ) {
            PLS_Database::install();
        }
    }

    public function register_rest_routes(): void {
        PLS_Rest_Api::register_routes();
    }

    public function register_elementor_widgets( $widgets_manager ): void {
        if ( file_exists( PLS_PLUGIN_DIR . 'elementor/class-pls-elementor-form-widget.php' ) ) {
            require_once PLS_PLUGIN_DIR . 'elementor/class-pls-elementor-form-widget.php';
            $widgets_manager->register( new PLS_Elementor_Form_Widget() );
        }
    }

    public function deactivate(): void {
        wp_clear_scheduled_hook( 'pls_process_queue' );
        wp_clear_scheduled_hook( 'pls_process_automations' );
    }
}

function pls_boot(): Leads_SaaS {
    return Leads_SaaS::instance();
}

add_action( 'plugins_loaded', 'pls_boot' );
