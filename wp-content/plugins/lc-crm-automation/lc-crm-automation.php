<?php
/**
 * Plugin Name: LC CRM AUTOMATION
 * Plugin URI:  https://lccrm.com
 * Description: Advanced CRM + Marketing Automation + WhatsApp + Email + Webhooks system for WordPress. Similar to ActiveCampaign / HubSpot, fully inside WordPress.
 * Version:     1.2.0
 * Author:      Dener Naresi
 * Author URI:  https://denernaresi.com
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lc-crm
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants
define( 'WPLA_VERSION', '1.2.0' );
define( 'WPLA_PLUGIN_FILE', __FILE__ );
define( 'WPLA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPLA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPLA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPLA_DB_VERSION', '1.2.0' );

/**
 * Main plugin class — singleton.
 */
final class LC_CRM_Automation {

    /** @var self|null */
    private static $instance = null;

    /** @var WPLA_Admin|null */
    private $admin = null;

    /**
     * Get singleton instance.
     */
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

    /**
     * Autoload all required files.
     */
    private function load_dependencies(): void {
        // Database installer
        require_once WPLA_PLUGIN_DIR . 'includes/class-wpla-database.php';

        // Models
        require_once WPLA_PLUGIN_DIR . 'includes/Models/class-wpla-contact.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Models/class-wpla-list.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Models/class-wpla-tag.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Models/class-wpla-email-template.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Models/class-wpla-webinar.php';

        // Services
        require_once WPLA_PLUGIN_DIR . 'includes/Services/class-wpla-event-manager.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Services/class-wpla-automation-engine.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Services/class-wpla-message-queue.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Services/class-wpla-lead-scoring.php';

        // Integrations
        require_once WPLA_PLUGIN_DIR . 'includes/Integrations/class-wpla-whatsapp.php';
        require_once WPLA_PLUGIN_DIR . 'includes/Integrations/class-wpla-email.php';

        // Controllers
        require_once WPLA_PLUGIN_DIR . 'includes/Controllers/class-wpla-form-controller.php';

        // REST API
        require_once WPLA_PLUGIN_DIR . 'includes/Api/class-wpla-rest-api.php';

        // Admin
        require_once WPLA_PLUGIN_DIR . 'admin/class-wpla-admin.php';
        if ( is_admin() ) {
            $this->admin = new WPLA_Admin();
        }

        // Elementor integration (conditional)
        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
    }

    /**
     * Register activation, deactivation and core hooks.
     */
    private function register_hooks(): void {
        register_activation_hook( WPLA_PLUGIN_FILE, array( 'WPLA_Database', 'install' ) );
        register_deactivation_hook( WPLA_PLUGIN_FILE, array( $this, 'deactivate' ) );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

        // Cron for queue processing
        add_action( 'wpla_process_queue', array( 'WPLA_Message_Queue', 'process' ) );
        add_action( 'wpla_process_automations', array( 'WPLA_Automation_Engine', 'process_delayed' ) );

        if ( ! wp_next_scheduled( 'wpla_process_queue' ) ) {
            wp_schedule_event( time(), 'every_minute', 'wpla_process_queue' );
        }
        if ( ! wp_next_scheduled( 'wpla_process_automations' ) ) {
            wp_schedule_event( time(), 'every_minute', 'wpla_process_automations' );
        }

        add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
    }

    /**
     * Add a one-minute cron interval.
     */
    public function add_cron_interval( array $schedules ): array {
        $schedules['every_minute'] = array(
            'interval' => 60,
            'display'  => __( 'A Cada Minuto', 'lc-crm' ),
        );
        return $schedules;
    }

    /**
     * Init hook — load text domain, start event manager, run DB upgrades.
     */
    public function init(): void {
        load_plugin_textdomain( 'lc-crm', false, dirname( WPLA_PLUGIN_BASENAME ) . '/languages' );
        WPLA_Event_Manager::instance();

        // Run DB upgrade if version changed.
        if ( get_option( 'wpla_db_version' ) !== WPLA_DB_VERSION ) {
            WPLA_Database::install();
        }
    }

    /**
     * Register REST API routes.
     */
    public function register_rest_routes(): void {
        WPLA_Rest_Api::register_routes();
    }

    /**
     * Register Elementor widgets.
     */
    public function register_elementor_widgets( $widgets_manager ): void {
        require_once WPLA_PLUGIN_DIR . 'elementor/class-wpla-elementor-form-widget.php';
        $widgets_manager->register( new WPLA_Elementor_Form_Widget() );
    }

    /**
     * Plugin deactivation — clear scheduled events.
     */
    public function deactivate(): void {
        wp_clear_scheduled_hook( 'wpla_process_queue' );
        wp_clear_scheduled_hook( 'wpla_process_automations' );
    }
}

/**
 * Boot the plugin.
 */
function wpla_boot(): LC_CRM_Automation {
    return LC_CRM_Automation::instance();
}

add_action( 'plugins_loaded', 'wpla_boot' );
