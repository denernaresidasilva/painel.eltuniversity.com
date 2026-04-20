<?php
/**
 * Automation Engine — evaluates triggers, conditions, and executes actions.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Automation_Engine {

    /**
     * Called when an event fires — find matching automations and start them.
     */
    public static function trigger( string $event_type, int $contact_id, $data = array() ): void {
        global $wpdb;
        $table = WPLA_Database::table( 'automations' );

        $automations = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE trigger_type = %s AND status = 'active'",
            $event_type
        ) );

        foreach ( $automations as $automation ) {
            self::start_automation( $automation, $contact_id, $data );
        }
    }

    /**
     * Start an automation for a contact — enqueue the first step.
     */
    private static function start_automation( object $automation, int $contact_id, $data ): void {
        global $wpdb;
        $steps_table = WPLA_Database::table( 'automation_steps' );

        // Get the first step (after trigger).
        $first_step = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $steps_table WHERE automation_id = %d AND step_type != 'trigger' ORDER BY step_order ASC LIMIT 1",
            $automation->id
        ) );

        if ( ! $first_step ) {
            return;
        }

        // Check if this contact already has a running instance.
        $logs_table = WPLA_Database::table( 'automation_logs' );
        $running    = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $logs_table WHERE automation_id = %d AND contact_id = %d AND status IN ('pending','running','waiting')",
            $automation->id, $contact_id
        ) );

        if ( $running ) {
            return; // Don't re-enqueue.
        }

        self::enqueue_step( $automation->id, $first_step->id, $contact_id );
    }

    /**
     * Enqueue a step for execution.
     */
    public static function enqueue_step( int $automation_id, int $step_id, int $contact_id, ?string $execute_at = null ): void {
        global $wpdb;

        $wpdb->insert(
            WPLA_Database::table( 'automation_logs' ),
            array(
                'automation_id' => $automation_id,
                'step_id'       => $step_id,
                'contact_id'    => $contact_id,
                'status'        => $execute_at ? 'waiting' : 'pending',
                'execute_at'    => $execute_at ?: current_time( 'mysql' ),
            ),
            array( '%d', '%d', '%d', '%s', '%s' )
        );
    }

    /**
     * Process delayed / pending steps — called by WP Cron.
     */
    public static function process_delayed(): void {
        global $wpdb;
        $logs_table = WPLA_Database::table( 'automation_logs' );

        // Get steps that are ready to execute.
        $pending = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $logs_table WHERE status IN ('pending','waiting') AND execute_at <= %s ORDER BY execute_at ASC LIMIT 50",
            current_time( 'mysql' )
        ) );

        foreach ( $pending as $log ) {
            self::execute_step( $log );
        }
    }

    /**
     * Execute a single automation step.
     */
    private static function execute_step( object $log ): void {
        global $wpdb;
        $logs_table  = WPLA_Database::table( 'automation_logs' );
        $steps_table = WPLA_Database::table( 'automation_steps' );

        // Mark as running.
        $wpdb->update( $logs_table, array( 'status' => 'running' ), array( 'id' => $log->id ), array( '%s' ), array( '%d' ) );

        $step = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $steps_table WHERE id = %d", $log->step_id ) );

        if ( ! $step ) {
            $wpdb->update( $logs_table, array( 'status' => 'failed', 'result' => 'Step not found' ), array( 'id' => $log->id ) );
            return;
        }

        $config = json_decode( $step->config, true ) ?: array();
        $result = '';

        switch ( $step->step_type ) {
            case 'condition':
                $passed = self::evaluate_condition( $step, $log->contact_id, $config );
                $result = $passed ? 'condition_passed' : 'condition_failed';

                // If condition fails, find the "else" branch or stop.
                if ( ! $passed ) {
                    $else_step = $wpdb->get_row( $wpdb->prepare(
                        "SELECT * FROM $steps_table WHERE automation_id = %d AND parent_id = %d AND branch_label = 'else' ORDER BY step_order ASC LIMIT 1",
                        $log->automation_id, $step->id
                    ) );

                    if ( $else_step ) {
                        self::enqueue_step( $log->automation_id, $else_step->id, $log->contact_id );
                    }

                    $wpdb->update( $logs_table, array(
                        'status'       => 'completed',
                        'result'       => $result,
                        'completed_at' => current_time( 'mysql' ),
                    ), array( 'id' => $log->id ) );
                    return;
                }
                break;

            case 'action':
                $result = self::execute_action( $step, $log->contact_id, $config );
                break;

            case 'delay':
                $delay_amount = absint( $config['amount'] ?? 1 );
                $delay_unit   = sanitize_text_field( $config['unit'] ?? 'hours' );

                $units_map = array(
                    'minutes' => 'MINUTE',
                    'hours'   => 'HOUR',
                    'days'    => 'DAY',
                );
                $sql_unit = $units_map[ $delay_unit ] ?? 'HOUR';

                $execute_at = gmdate( 'Y-m-d H:i:s', strtotime( "+{$delay_amount} {$sql_unit}" ) );

                // Mark current as completed, enqueue next step with delay.
                $wpdb->update( $logs_table, array(
                    'status'       => 'completed',
                    'result'       => "delay:{$delay_amount}_{$delay_unit}",
                    'completed_at' => current_time( 'mysql' ),
                ), array( 'id' => $log->id ) );

                $next_step = self::get_next_step( $step );
                if ( $next_step ) {
                    self::enqueue_step( $log->automation_id, $next_step->id, $log->contact_id, $execute_at );
                }
                return;

            case 'branch':
                // Branch node — evaluate which path to take.
                $branch_steps = $wpdb->get_results( $wpdb->prepare(
                    "SELECT * FROM $steps_table WHERE automation_id = %d AND parent_id = %d ORDER BY step_order ASC",
                    $log->automation_id, $step->id
                ) );

                foreach ( $branch_steps as $branch_step ) {
                    self::enqueue_step( $log->automation_id, $branch_step->id, $log->contact_id );
                }

                $wpdb->update( $logs_table, array(
                    'status'       => 'completed',
                    'result'       => 'branched',
                    'completed_at' => current_time( 'mysql' ),
                ), array( 'id' => $log->id ) );
                return;
        }

        // Mark completed.
        $wpdb->update( $logs_table, array(
            'status'       => 'completed',
            'result'       => $result,
            'completed_at' => current_time( 'mysql' ),
        ), array( 'id' => $log->id ) );

        // Advance to next step.
        $next_step = self::get_next_step( $step );
        if ( $next_step ) {
            self::enqueue_step( $log->automation_id, $next_step->id, $log->contact_id );
        }
    }

    /**
     * Evaluate a condition step.
     */
    private static function evaluate_condition( object $step, int $contact_id, array $config ): bool {
        $type  = $config['condition_type'] ?? '';
        $value = $config['value'] ?? '';

        switch ( $type ) {
            case 'has_tag':
            case 'contains_tag':
            case 'received_tag':
                $tag = WPLA_Tag::get_by_name( $value );
                if ( ! $tag ) {
                    return false;
                }
                $tags = WPLA_Contact::get_tags( $contact_id );
                foreach ( $tags as $t ) {
                    if ( (int) $t->id === (int) $tag->id ) {
                        return true;
                    }
                }
                return false;

            case 'in_list':
                $lists = WPLA_Contact::get_lists( $contact_id );
                foreach ( $lists as $l ) {
                    if ( (int) $l->id === (int) $value ) {
                        return true;
                    }
                }
                return false;

            case 'score_above':
                $contact = WPLA_Contact::get( $contact_id );
                return $contact && (int) $contact->lead_score >= (int) $value;

            case 'field_equals':
                $field   = sanitize_text_field( $config['field'] ?? '' );
                $contact = WPLA_Contact::get( $contact_id );
                if ( $contact && isset( $contact->$field ) ) {
                    return $contact->$field === $value;
                }
                return false;

            case 'email_opened':
                // True if the contact has opened an email within the last X hours (value = hours).
                $hours   = max( 1, (int) $value );
                $since   = gmdate( 'Y-m-d H:i:s', strtotime( "-{$hours} hours" ) );
                $contact = WPLA_Contact::get( $contact_id );
                return $contact && ! empty( $contact->last_email_opened_at ) && $contact->last_email_opened_at >= $since;

            case 'email_clicked':
                // True if the contact clicked a link within the last X hours (value = hours).
                $hours   = max( 1, (int) $value );
                $since   = gmdate( 'Y-m-d H:i:s', strtotime( "-{$hours} hours" ) );
                $contact = WPLA_Contact::get( $contact_id );
                return $contact && ! empty( $contact->last_email_clicked_at ) && $contact->last_email_clicked_at >= $since;

            case 'email_not_opened':
                // True if the contact has NOT opened an email in the last X hours.
                $hours   = max( 1, (int) $value );
                $since   = gmdate( 'Y-m-d H:i:s', strtotime( "-{$hours} hours" ) );
                $contact = WPLA_Contact::get( $contact_id );
                if ( ! $contact ) {
                    return false;
                }
                return empty( $contact->last_email_opened_at ) || $contact->last_email_opened_at < $since;

            default:
                return (bool) apply_filters( 'wpla_condition_evaluate', false, $type, $contact_id, $config );
        }
    }

    /**
     * Execute an action step.
     */
    private static function execute_action( object $step, int $contact_id, array $config ): string {
        $action = $config['action_type'] ?? $step->action_type;

        switch ( $action ) {
            case 'add_tag':
                $tag_name = sanitize_text_field( $config['tag_name'] ?? '' );
                $tag      = WPLA_Tag::get_by_name( $tag_name );
                if ( ! $tag ) {
                    $tag_id = WPLA_Tag::create( array( 'name' => $tag_name ) );
                } else {
                    $tag_id = $tag->id;
                }
                WPLA_Contact::add_tag( $contact_id, (int) $tag_id );
                return "tag_added:{$tag_name}";

            case 'remove_tag':
                $tag_name = sanitize_text_field( $config['tag_name'] ?? '' );
                $tag      = WPLA_Tag::get_by_name( $tag_name );
                if ( $tag ) {
                    WPLA_Contact::remove_tag( $contact_id, (int) $tag->id );
                }
                return "tag_removed:{$tag_name}";

            case 'subscribe_list':
                $list_id = absint( $config['list_id'] ?? 0 );
                if ( $list_id ) {
                    WPLA_Contact::subscribe_list( $contact_id, $list_id );
                }
                return "subscribed_list:{$list_id}";

            case 'unsubscribe_list':
                $list_id = absint( $config['list_id'] ?? 0 );
                if ( $list_id ) {
                    WPLA_Contact::unsubscribe_list( $contact_id, $list_id );
                }
                return "unsubscribed_list:{$list_id}";

            case 'route_webinar':
                $webinar_id  = absint( $config['webinar_id'] ?? 0 );
                $destination = sanitize_text_field( $config['destination'] ?? '' );
                if ( $webinar_id && $destination ) {
                    WPLA_Webinar::route_contact( $webinar_id, $contact_id, $destination );
                }
                return "webinar_routed:{$webinar_id}:{$destination}";

            case 'send_email':
                $template_id = absint( $config['template_id'] ?? 0 );
                $subject     = sanitize_text_field( $config['subject'] ?? '' );
                $body        = wp_kses_post( $config['body'] ?? '' );
                $contact     = WPLA_Contact::get( $contact_id );

                // Load subject from template if not explicitly set.
                if ( $template_id && empty( $subject ) ) {
                    $tpl     = WPLA_Email_Template::get( $template_id );
                    $subject = $tpl ? $tpl->subject : '';
                }

                if ( $contact ) {
                    WPLA_Message_Queue::enqueue( $contact_id, 'email', $contact->email, $subject, $body, null, $template_id );
                }
                return "email_queued:{$subject}";

            case 'send_whatsapp':
                $message = sanitize_text_field( $config['message'] ?? '' );
                $contact = WPLA_Contact::get( $contact_id );
                if ( $contact && $contact->phone ) {
                    WPLA_Message_Queue::enqueue( $contact_id, 'whatsapp', $contact->phone, '', $message );
                }
                return "whatsapp_queued";

            case 'update_field':
                $field = sanitize_text_field( $config['field'] ?? '' );
                $val   = sanitize_text_field( $config['value'] ?? '' );
                if ( $field ) {
                    WPLA_Contact::update( $contact_id, array( $field => $val ) );
                }
                return "field_updated:{$field}";

            case 'update_score':
                $delta = (int) ( $config['score_delta'] ?? 0 );
                $contact = WPLA_Contact::get( $contact_id );
                if ( $contact ) {
                    $new_score = max( 0, (int) $contact->lead_score + $delta );
                    WPLA_Contact::update( $contact_id, array( 'lead_score' => $new_score ) );
                }
                return "score_updated:{$delta}";

            case 'webhook':
                $url  = esc_url_raw( $config['url'] ?? '' );
                $contact = WPLA_Contact::get( $contact_id );
                if ( $url && $contact ) {
                    wp_remote_post( $url, array(
                        'body'    => wp_json_encode( (array) $contact ),
                        'headers' => array( 'Content-Type' => 'application/json' ),
                        'timeout' => 15,
                    ) );
                }
                return "webhook_sent:{$url}";

            default:
                do_action( 'wpla_action_execute', $action, $contact_id, $config );
                return "custom_action:{$action}";
        }
    }

    /**
     * Get the next step in the automation sequence.
     */
    private static function get_next_step( object $current_step ): ?object {
        global $wpdb;
        $table = WPLA_Database::table( 'automation_steps' );

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE automation_id = %d AND step_order > %d AND parent_id = %d ORDER BY step_order ASC LIMIT 1",
            $current_step->automation_id, $current_step->step_order, $current_step->parent_id
        ) );
    }

    /* ───── CRUD helpers for admin UI ───── */

    public static function get_automation( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'automations' ) . ' WHERE id = %d', $id
        ) ) ?: null;
    }

    public static function get_steps( int $automation_id ): array {
        global $wpdb;
        return $wpdb->get_results( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'automation_steps' ) . ' WHERE automation_id = %d ORDER BY step_order ASC',
            $automation_id
        ) );
    }

    public static function save_automation( array $data ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'automations' );

        $id = absint( $data['id'] ?? 0 );

        $row = array(
            'name'           => sanitize_text_field( $data['name'] ?? '' ),
            'description'    => sanitize_textarea_field( $data['description'] ?? '' ),
            'trigger_type'   => sanitize_text_field( $data['trigger_type'] ?? 'contact_created' ),
            'trigger_config' => isset( $data['trigger_config'] ) ? wp_json_encode( $data['trigger_config'] ) : '{}',
            'status'         => in_array( $data['status'] ?? '', array( 'active', 'paused', 'draft' ), true ) ? $data['status'] : 'draft',
        );

        if ( $id ) {
            $wpdb->update( $table, $row, array( 'id' => $id ), array( '%s','%s','%s','%s','%s' ), array( '%d' ) );
        } else {
            $wpdb->insert( $table, $row, array( '%s','%s','%s','%s','%s' ) );
            $id = (int) $wpdb->insert_id;
        }

        return $id;
    }

    public static function save_steps( int $automation_id, array $steps ): void {
        global $wpdb;
        $table = WPLA_Database::table( 'automation_steps' );

        // Remove old steps.
        $wpdb->delete( $table, array( 'automation_id' => $automation_id ), array( '%d' ) );

        foreach ( $steps as $order => $step ) {
            $wpdb->insert( $table, array(
                'automation_id' => $automation_id,
                'parent_id'     => absint( $step['parent_id'] ?? 0 ),
                'step_type'     => sanitize_text_field( $step['step_type'] ?? 'action' ),
                'action_type'   => sanitize_text_field( $step['action_type'] ?? '' ),
                'config'        => isset( $step['config'] ) ? wp_json_encode( $step['config'] ) : '{}',
                'step_order'    => (int) ( $step['step_order'] ?? $order ),
                'branch_label'  => sanitize_text_field( $step['branch_label'] ?? '' ),
            ), array( '%d','%d','%s','%s','%s','%d','%s' ) );
        }
    }

    public static function delete_automation( int $id ): bool {
        global $wpdb;
        $wpdb->delete( WPLA_Database::table( 'automation_steps' ), array( 'automation_id' => $id ), array( '%d' ) );
        $wpdb->delete( WPLA_Database::table( 'automation_logs' ), array( 'automation_id' => $id ), array( '%d' ) );
        return (bool) $wpdb->delete( WPLA_Database::table( 'automations' ), array( 'id' => $id ), array( '%d' ) );
    }

    public static function all_automations(): array {
        global $wpdb;
        return $wpdb->get_results( 'SELECT * FROM ' . WPLA_Database::table( 'automations' ) . ' ORDER BY created_at DESC' );
    }

    public static function count_active(): int {
        global $wpdb;
        return (int) $wpdb->get_var( $wpdb->prepare(
            'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'automations' ) . ' WHERE status = %s', 'active'
        ) );
    }
}
