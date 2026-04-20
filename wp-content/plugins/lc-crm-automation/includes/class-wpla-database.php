<?php
/**
 * Database installer — creates all custom tables on plugin activation.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Database {

    /**
     * Run on plugin activation. Creates / updates all tables.
     */
    public static function install(): void {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $prefix          = $wpdb->prefix . 'wpla_';

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // 1. Contacts
        $sql = "CREATE TABLE {$prefix}contacts (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            email VARCHAR(191) NOT NULL,
            first_name VARCHAR(100) DEFAULT '',
            last_name VARCHAR(100) DEFAULT '',
            phone VARCHAR(50) DEFAULT '',
            company VARCHAR(191) DEFAULT '',
            status ENUM('active','unsubscribed','bounced') DEFAULT 'active',
            lead_score INT DEFAULT 0,
            source VARCHAR(100) DEFAULT '',
            utm_source VARCHAR(191) DEFAULT '',
            utm_medium VARCHAR(191) DEFAULT '',
            utm_campaign VARCHAR(191) DEFAULT '',
            utm_content VARCHAR(191) DEFAULT '',
            utm_term VARCHAR(191) DEFAULT '',
            ip_address VARCHAR(45) DEFAULT '',
            custom_fields LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email),
            KEY status (status),
            KEY lead_score (lead_score),
            KEY created_at (created_at)
        ) $charset_collate;";
        dbDelta( $sql );

        // 2. Lists
        $sql = "CREATE TABLE {$prefix}lists (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            description TEXT DEFAULT NULL,
            status ENUM('active','archived') DEFAULT 'active',
            list_type VARCHAR(50) DEFAULT 'general',
            webinar_id BIGINT UNSIGNED DEFAULT 0,
            form_fields LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status (status),
            KEY webinar_id (webinar_id)
        ) $charset_collate;";
        dbDelta( $sql );

        // 3. Tags
        $sql = "CREATE TABLE {$prefix}tags (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            color VARCHAR(7) DEFAULT '#6366f1',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY name (name)
        ) $charset_collate;";
        dbDelta( $sql );

        // 4. Contact ↔ Tags pivot
        $sql = "CREATE TABLE {$prefix}contact_tags (
            contact_id BIGINT UNSIGNED NOT NULL,
            tag_id BIGINT UNSIGNED NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (contact_id, tag_id),
            KEY tag_id (tag_id)
        ) $charset_collate;";
        dbDelta( $sql );

        // 5. Contact ↔ Lists pivot
        $sql = "CREATE TABLE {$prefix}contact_lists (
            contact_id BIGINT UNSIGNED NOT NULL,
            list_id BIGINT UNSIGNED NOT NULL,
            status ENUM('subscribed','unsubscribed') DEFAULT 'subscribed',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (contact_id, list_id),
            KEY list_id (list_id)
        ) $charset_collate;";
        dbDelta( $sql );

        // 6. Automations
        $sql = "CREATE TABLE {$prefix}automations (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            description TEXT DEFAULT NULL,
            trigger_type VARCHAR(100) NOT NULL,
            trigger_config LONGTEXT DEFAULT NULL,
            status ENUM('active','paused','draft') DEFAULT 'draft',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status (status),
            KEY trigger_type (trigger_type)
        ) $charset_collate;";
        dbDelta( $sql );

        // 7. Automation steps (nodes in the flow)
        $sql = "CREATE TABLE {$prefix}automation_steps (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            automation_id BIGINT UNSIGNED NOT NULL,
            parent_id BIGINT UNSIGNED DEFAULT 0,
            step_type ENUM('trigger','condition','action','delay','branch') NOT NULL,
            action_type VARCHAR(100) DEFAULT '',
            config LONGTEXT DEFAULT NULL,
            step_order INT DEFAULT 0,
            branch_label VARCHAR(100) DEFAULT '',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY automation_id (automation_id),
            KEY parent_id (parent_id),
            KEY step_order (step_order)
        ) $charset_collate;";
        dbDelta( $sql );

        // 8. Events log
        $sql = "CREATE TABLE {$prefix}events (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            event_type VARCHAR(100) NOT NULL,
            contact_id BIGINT UNSIGNED DEFAULT 0,
            data LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY event_type (event_type),
            KEY contact_id (contact_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        dbDelta( $sql );

        // 9. Message queue (email + WhatsApp)
        $sql = "CREATE TABLE {$prefix}message_queue (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            contact_id BIGINT UNSIGNED NOT NULL,
            channel ENUM('email','whatsapp') NOT NULL,
            recipient VARCHAR(191) NOT NULL,
            subject VARCHAR(255) DEFAULT '',
            body LONGTEXT NOT NULL,
            template_id BIGINT UNSIGNED DEFAULT 0,
            status ENUM('pending','processing','sent','opened','failed') DEFAULT 'pending',
            attempts INT DEFAULT 0,
            scheduled_at DATETIME DEFAULT NULL,
            sent_at DATETIME DEFAULT NULL,
            error_message TEXT DEFAULT NULL,
            tracking_id VARCHAR(64) DEFAULT '',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status (status),
            KEY channel (channel),
            KEY scheduled_at (scheduled_at),
            KEY contact_id (contact_id),
            KEY tracking_id (tracking_id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Extend message_queue ENUM to include 'opened' on existing installs — only if needed.
        $mq_table  = $prefix . 'message_queue';
        $mq_col    = $wpdb->get_row( "SHOW COLUMNS FROM $mq_table LIKE 'status'" );
        if ( $mq_col && strpos( $mq_col->Type, "'opened'" ) === false ) {
            $wpdb->query( "ALTER TABLE $mq_table MODIFY COLUMN status ENUM('pending','processing','sent','opened','failed') DEFAULT 'pending'" );
        }

        // 10. Automation execution logs
        $sql = "CREATE TABLE {$prefix}automation_logs (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            automation_id BIGINT UNSIGNED NOT NULL,
            step_id BIGINT UNSIGNED NOT NULL,
            contact_id BIGINT UNSIGNED NOT NULL,
            status ENUM('pending','running','completed','failed','waiting') DEFAULT 'pending',
            result LONGTEXT DEFAULT NULL,
            execute_at DATETIME DEFAULT NULL,
            completed_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY automation_id (automation_id),
            KEY contact_id (contact_id),
            KEY status (status),
            KEY execute_at (execute_at)
        ) $charset_collate;";
        dbDelta( $sql );

        // 11. Webinars
        $sql = "CREATE TABLE {$prefix}webinars (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            description TEXT DEFAULT NULL,
            video_type ENUM('youtube','vimeo','html5') DEFAULT 'youtube',
            video_url TEXT DEFAULT NULL,
            offer_title VARCHAR(255) DEFAULT '',
            offer_url TEXT DEFAULT NULL,
            offer_button_text VARCHAR(191) DEFAULT '',
            offer_time_live INT UNSIGNED DEFAULT 0,
            offer_time_replay INT UNSIGNED DEFAULT 0,
            automation_id BIGINT UNSIGNED DEFAULT 0,
            status ENUM('active','paused','draft') DEFAULT 'draft',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status (status)
        ) $charset_collate;";
        dbDelta( $sql );

        // 12. Email templates (reusable HTML blocks for automation actions)
        $sql = "CREATE TABLE {$prefix}email_templates (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            subject VARCHAR(255) NOT NULL DEFAULT '',
            body LONGTEXT NOT NULL,
            status ENUM('active','draft') DEFAULT 'draft',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status (status)
        ) $charset_collate;";
        dbDelta( $sql );

        // Extend contacts with email engagement columns (ALTER is safe via dbDelta for new cols).
        $contacts_table = $prefix . 'contacts';
        $col_check      = $wpdb->get_results( "SHOW COLUMNS FROM $contacts_table LIKE 'email_status'" );
        if ( empty( $col_check ) ) {
            $wpdb->query( "ALTER TABLE $contacts_table
                ADD COLUMN email_status VARCHAR(50) DEFAULT 'active' AFTER status,
                ADD COLUMN last_email_sent DATETIME DEFAULT NULL,
                ADD COLUMN last_email_opened_at DATETIME DEFAULT NULL,
                ADD COLUMN last_email_clicked_at DATETIME DEFAULT NULL" );
        }

        update_option( 'wpla_db_version', WPLA_DB_VERSION );
        update_option( 'wpla_installed_at', current_time( 'mysql' ) );

        // Generate default API key if not set.
        if ( ! get_option( 'wpla_api_key' ) ) {
            update_option( 'wpla_api_key', wp_generate_password( 40, false ) );
        }
    }

    /**
     * Helper: get table name with prefix.
     */
    public static function table( string $name ): string {
        global $wpdb;
        return $wpdb->prefix . 'wpla_' . $name;
    }
}
