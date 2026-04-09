<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
class WebinarignitionAjax {
    public static function webinarignition_add_lead_check_secure() {
        if ( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( array(
                'message' => 'Invalid nonce',
            ) );
            wp_die();
            // terminate the script if nonce is invalid
        }
        $app_id = ( isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : null );
        $email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null );
        if ( empty( $email ) || empty( $app_id ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
        if ( empty( $webinar_data ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $user = get_user_by( 'email', $email );
        if ( !WebinarignitionPowerups::webinarignition_is_secure_access_enabled( $webinar_data ) ) {
            self::webinarignition_success_response( array(
                'message' => __( 'Success', 'webinar-ignition' ) . ': ' . __( 'Great lets register.', 'webinar-ignition' ),
            ) );
        }
        $secure_access_webinar_blacklisted = array();
        $secure_access_webinar_whitelisted = array();
        if ( !empty( $webinar_data->secure_access_webinar_blacklisted ) ) {
            $secure_access_webinar_blacklisted = explode( ',', $webinar_data->secure_access_webinar_blacklisted );
        }
        if ( !empty( $secure_access_webinar_blacklisted ) ) {
            foreach ( $secure_access_webinar_blacklisted as $blacklisted ) {
                $blacklisted = trim( $blacklisted );
                if ( false !== strpos( $email, $blacklisted ) ) {
                    self::error_response( array(
                        'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'You are not authorized to register for the event.', 'webinar-ignition' ),
                    ) );
                }
            }
        }
        $is_whitelisted = true;
        if ( !empty( $webinar_data->secure_access_webinar_whitelisted ) ) {
            $is_whitelisted = false;
            $secure_access_webinar_whitelisted = explode( ',', $webinar_data->secure_access_webinar_whitelisted );
        }
        if ( !empty( $secure_access_webinar_whitelisted ) ) {
            foreach ( $secure_access_webinar_whitelisted as $whitelisted ) {
                $whitelisted = trim( $whitelisted );
                if ( false !== strpos( $email, $whitelisted ) ) {
                    $is_whitelisted = true;
                    break;
                }
            }
        }
        if ( empty( $is_whitelisted ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'You are not authorized to register for the event.', 'webinar-ignition' ),
            ) );
        }
        self::webinarignition_success_response( array(
            'message' => __( 'Success', 'webinar-ignition' ) . ': ' . __( 'Great lets register.', 'webinar-ignition' ),
        ) );
    }

    public static function webinarignition_register_support() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) && !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        $data = self::webinarignition_get_form_data();
        if ( empty( $data['app_id'] ) || empty( $data['support_stuff_url'] ) && empty( $data['host_presenters_url'] ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $app_id = sanitize_text_field( $data['app_id'] );
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
        if ( empty( $webinar_data ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $host_presenters_url = sanitize_text_field( $data['host_presenters_url'] );
        $support_stuff_url = sanitize_text_field( $data['support_stuff_url'] );
        unset($data['app_id']);
        unset($data['host_presenters_url']);
        unset($data['support_stuff_url']);
        $errors = array();
        foreach ( $data as $field => $value ) {
            if ( empty( $value ) ) {
                $errors[$field] = __( 'Field required', 'webinar-ignition' );
            } elseif ( 'email' === $field && empty( filter_var( $value, FILTER_VALIDATE_EMAIL ) ) ) {
                $errors[$field] = __( 'Wrong email', 'webinar-ignition' );
            } else {
                if ( 'email' === $field ) {
                    $value = filter_var( $value, FILTER_VALIDATE_EMAIL );
                } else {
                    $value = sanitize_text_field( $value );
                }
                $data[$field] = $value;
            }
        }
        if ( !empty( $errors ) ) {
            self::error_response( array(
                'errors' => $errors,
            ) );
        }
        $_wi_support_token = WebinarignitionManager::webinarignition_register_support(
            $app_id,
            $data,
            $host_presenters_url,
            $support_stuff_url
        );
        if ( empty( $_wi_support_token ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!', 'webinar-ignition' ),
            ) );
        }
        self::webinarignition_success_response( array(
            'data' => $data,
        ) );
    }

    public static function webinarignition_submit_chat_question() {
        $app_id = ( isset( $_POST['app_id'] ) ? sanitize_text_field( $_POST['app_id'] ) : null );
        $name = ( isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null );
        $email = ( isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : null );
        $question = ( isset( $_POST['question'] ) ? htmlspecialchars( $_POST['question'] ) : null );
        $webinarTime = ( isset( $_POST['webinarTime'] ) ? sanitize_text_field( $_POST['webinarTime'] ) : null );
        $is_first_question = ( isset( $_POST['is_first_question'] ) ? $_POST['is_first_question'] : null );
        $last_message = ( isset( $_POST['last_message'] ) ? sanitize_text_field( $_POST['last_message'] ) : null );
        if ( empty( $app_id ) || empty( $name ) || empty( $email ) || empty( $question ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
        if ( empty( $webinar_data ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $data = array(
            'app_id'   => $app_id,
            'name'     => $name,
            'email'    => $email,
            'question' => $question,
            'type'     => 'question',
            'status'   => 'live',
            'created'  => current_time( 'mysql' ),
        );
        if ( !empty( $webinarTime ) ) {
            $data['webinarTime'] = $webinarTime;
        }
        $id = WebinarignitionQA::webinarignition_create_question( $data );
        $data['webinar_type'] = ( 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' );
        $data['is_first_question'] = $is_first_question;
        do_action( 'webinarignition_question_asked', $data );
        if ( !empty( $last_message ) ) {
            $chat_messages = WebinarignitionQA::webinarignition_get_chat_messages(
                $app_id,
                $data['email'],
                null,
                'AND ID > ' . $last_message
            );
        } else {
            $chat_messages = WebinarignitionQA::webinarignition_get_chat_messages( $app_id, $data['email'] );
        }
        $chat_messages_deleted = WebinarignitionQA::webinarignition_get_chat_messages(
            $app_id,
            $data['email'],
            null,
            'AND status IN ("deleted")'
        );
        self::webinarignition_success_response( array(
            'chat_messages'         => $chat_messages,
            'chat_messages_deleted' => $chat_messages_deleted,
        ) );
    }

    public static function webinarignition_load_chat_messages() {
        // ! TODO: Use nonce verification.
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        if ( empty( $_POST['app_id'] ) || empty( $_POST['email'] ) ) {
            $params = array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
                'reload'  => 1,
            );
            self::error_response( $params );
        }
        $app_id = sanitize_text_field( $_POST['app_id'] );
        $email = sanitize_text_field( $_POST['email'] );
        $params = array(
            'chat_messages' => WebinarignitionQA::webinarignition_get_chat_messages(
                $app_id,
                $email,
                null,
                ' AND status NOT IN ("deleted")'
            ),
            'reload'        => 0,
        );
        self::webinarignition_success_response( $params );
    }

    public static function webinarignition_refresh_chat_messages() {
        // ! TODO: Use nonce verification
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        if ( empty( $_POST['app_id'] ) || empty( $_POST['email'] ) ) {
            $params = array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
                'reload'  => 0,
            );
            self::error_response( $params );
        }
        $app_id = sanitize_text_field( $_POST['app_id'] );
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
        if ( empty( $webinar_data ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $email = sanitize_text_field( $_POST['email'] );
        $last_message = ( !empty( $_POST['last_message'] ) ? sanitize_text_field( $_POST['last_message'] ) : false );
        if ( !empty( $last_message ) ) {
            $chat_messages = WebinarignitionQA::webinarignition_get_chat_messages(
                $app_id,
                $email,
                null,
                'AND ID > ' . $last_message
            );
        } else {
            $chat_messages = WebinarignitionQA::webinarignition_get_chat_messages( $app_id, $email );
        }
        $chat_messages_deleted = WebinarignitionQA::webinarignition_get_chat_messages(
            $app_id,
            $email,
            null,
            'AND status IN ("deleted")'
        );
        self::webinarignition_success_response( array(
            'chat_messages'         => $chat_messages,
            'chat_messages_deleted' => $chat_messages_deleted,
        ) );
    }

    public static function webinarignition_activate_freemius() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        $is_active = get_option( 'webinarignition_activate_freemius' );
        if ( !empty( $is_active ) ) {
            delete_option( 'webinarignition_activate_freemius' );
            $message = __( 'Freemius disabled!!', 'webinar-ignition' );
        } else {
            update_option( 'webinarignition_activate_freemius', 1 );
            $message = __( "Let's see what we have for you!!!", 'webinar-ignition' );
        }
        $params = array(
            'message' => $message,
            'reload'  => 1,
        );
        self::webinarignition_success_response( $params );
    }

    public static function webinarignition_deactivate_freemius() {
        $activate_freemius = get_option( 'webinarignition_activate_freemius' );
        $params = array(
            'message' => __( 'Hooray!!!', 'webinar-ignition' ) . ' ' . __( "Let's see what we have for you!!!", 'webinar-ignition' ),
            'reload'  => 1,
        );
        self::webinarignition_success_response( $params );
    }

    public static function webinarignition_track_is_live() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        // ! TODO: Use nonce verification.
        if ( empty( sanitize_text_field( $_POST['action'] ) ) || empty( sanitize_text_field( $_POST['cookie'] ) ) || empty( sanitize_text_field( $_POST['page'] ) ) || empty( sanitize_text_field( $_POST['webinar_id'] ) ) || empty( sanitize_text_field( $_POST['webinar_type'] ) ) || empty( sanitize_text_field( $_POST['lead_id'] ) ) || empty( sanitize_text_field( $_POST['start'] ) ) || empty( sanitize_text_field( $_POST['current'] ) ) ) {
            $params = array(
                'return' => 'missing_args',
                'post'   => $_POST,
            );
            self::error_response( $params );
        }
        $status = sanitize_text_field( $_POST['status'] );
        unset($_POST['action']);
        unset($_POST['status']);
        $lead_id = sanitize_text_field( $_POST['lead_id'] );
        $cookie = sanitize_text_field( $_POST['cookie'] );
        $page = sanitize_text_field( $_POST['page'] );
        $webinar_id = sanitize_text_field( $_POST['webinar_id'] );
        $webinar_type = sanitize_text_field( $_POST['webinar_type'] );
        $post_start = ( isset( $_POST['start'] ) ? absint( $_POST['start'] ) : 0 );
        if ( $post_start > 0 ) {
            $post_start = $post_start / 1000;
        }
        $post_current = ( isset( $_POST['current'] ) ? absint( $_POST['current'] ) : 0 );
        if ( $post_current > 0 ) {
            $post_current = $post_current / 1000;
        }
        $start = get_gmt_from_date( gmdate( 'Y-m-d H:i:s', $post_start ), 'U' );
        $current = get_gmt_from_date( gmdate( 'Y-m-d H:i:s', $post_current ), 'U' );
        $now = get_gmt_from_date( gmdate( 'Y-m-d H:i:s', time() ), 'U' );
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
        $result = compact( array_keys( $_POST ) );
        $option_is_alive = get_transient( 'webinarignition_is_alive_' . $webinar_type . '_' . $lead_id );
        if ( empty( $option_is_alive ) ) {
            $option_is_alive = array();
        }
        if ( empty( $option_is_alive['cookie'] ) ) {
            set_transient( 'webinarignition_is_alive_' . $webinar_type . '_' . $lead_id, $result, 60 * 60 * 24 );
            self::webinarignition_success_response( array(
                'return' => 'first_join',
                'post'   => $_POST,
            ) );
        } elseif ( $option_is_alive['cookie'] === $cookie ) {
            set_transient( 'webinarignition_is_alive_' . $webinar_type . '_' . $lead_id, $result, 60 * 60 * 24 );
            self::webinarignition_success_response( array(
                'return' => 'rejoin',
                'post'   => $_POST,
            ) );
        } elseif ( $current - $option_is_alive['current'] > 25 ) {
            // update_option('webinarignition_is_alive_' . $webinar_type . '_' . $lead_id, $result);
            set_transient( 'webinarignition_is_alive_' . $webinar_type . '_' . $lead_id, $result, 60 * 60 * 24 );
            self::webinarignition_success_response( array(
                'return' => 'another_device_join',
                'reload' => 1,
                'post'   => $_POST,
            ) );
        } else {
            if ( 'initial' === $status ) {
                $timer = ( !empty( $webinar_data->limit_lead_timer ) ? (int) $webinar_data->limit_lead_timer : 30 );
                WebinarignitionManager::webinarignition_set_locale( $webinar_data );
                ob_start();
                ?>
					<p style="margin: 5px"><?php 
                echo esc_html__( 'Looks like you already watching this webinar on another device/browser.', 'webinar-ignition' );
                ?></p>
					<p style="margin: 5px"><?php 
                echo esc_html__( 'To continue watching here you need to logout from another device.', 'webinar-ignition' );
                ?></p>
					<p style="margin: 5px">
				<?php 
                /* translators: %s: Number of seconds until redirection */
                printf( ' ' . esc_html__( 'Otherwise you will be redirected to registration page in %s seconds.', 'webinar-ignition' ), '<strong id="not_allowed_timer">' . absint( $timer ) . '</strong>' );
                ?>
					</p>
					<?php 
                WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
                $message = ob_get_clean();
                $permalink = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'registration' );
                $params = array(
                    'return'            => 'not_allowed',
                    'post'              => $_POST,
                    'pending_permalink' => $permalink,
                    'pending_message'   => $message,
                    'pending_timer'     => $timer,
                );
            } else {
                $params = array(
                    'return' => 'not_allowed',
                    'post'   => $_POST,
                );
            }
            //end if
            self::error_response( $params );
        }
        //end if
        self::webinarignition_success_response( $params );
    }

    public static function webinarignition_tracking_tags() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        // ! TODO: Use nonce verification.
        if ( empty( sanitize_text_field( $_POST['time'] ) ) || empty( sanitize_text_field( $_POST['name'] ) ) || empty( sanitize_text_field( $_POST['lead_id'] ) ) || empty( sanitize_text_field( $_POST['webinar_type'] ) ) || empty( sanitize_text_field( $_POST['webinar_id'] ) ) ) {
            $params = array(
                'return' => 'missing_args',
                'post'   => $_POST,
            );
            self::error_response( $params );
        }
        $time = sanitize_text_field( $_POST['time'] );
        $name = sanitize_text_field( $_POST['name'] );
        $lead_id = sanitize_text_field( $_POST['lead_id'] );
        $webinar_type = sanitize_text_field( $_POST['webinar_type'] );
        $webinar_id = sanitize_text_field( $_POST['webinar_id'] );
        $tracking_tags = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead_id, 'tracking_tags', $webinar_type );
        if ( !empty( $tracking_tags['meta_value'] ) ) {
            $tracking_tags_array = explode( ',', $tracking_tags['meta_value'] );
            if ( !in_array( $name, $tracking_tags_array, true ) ) {
                $tracking_tags_array[] = $name;
            }
        } else {
            $tracking_tags_array = array($name);
        }
        $tracking_tags = implode( ',', $tracking_tags_array );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $lead_id,
            'tracking_tags',
            $tracking_tags,
            $webinar_type
        );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $lead_id,
            'tracking_tags_last',
            $name,
            $webinar_type
        );
        if ( !empty( sanitize_text_field( $_POST['slug'] ) ) ) {
            $slug = sanitize_text_field( $_POST['slug'] );
            $tracking_tags = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead_id, $slug, $webinar_type );
            if ( !empty( $tracking_tags['meta_value'] ) ) {
                $tracking_tags_array = explode( ',', $tracking_tags['meta_value'] );
                if ( !in_array( $name, $tracking_tags_array, true ) ) {
                    $tracking_tags_array[] = $name;
                }
            } else {
                $tracking_tags_array = array($name);
            }
            $tracking_tags = implode( ',', $tracking_tags_array );
            WebinarignitionLeadsManager::webinarignition_update_lead_meta(
                $lead_id,
                $slug,
                $tracking_tags,
                $webinar_type
            );
            WebinarignitionLeadsManager::webinarignition_update_lead_meta(
                $lead_id,
                $slug . '_last',
                $name,
                $webinar_type
            );
        }
        $params = array(
            'return' => 'tag_saved',
            'post'   => $_POST,
        );
        self::webinarignition_success_response( $params );
    }

    public static function webinarignition_get_form_data() {
        // ! TODO: Use nonce verification.
        if ( empty( $_POST['formData'] ) || !is_array( $_POST['formData'] ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!', 'webinar-ignition' ),
            ) );
        }
        $data = array();
        foreach ( $_POST['formData'] as $form_data ) {
            $data[sanitize_text_field( $form_data['name'] )] = $form_data['value'];
        }
        return $data;
    }

    public static function error_response( $params = array() ) {
        $response = array(
            'success' => 0,
            'error'   => 1,
        );
        if ( !empty( $params ) ) {
            $response = array_merge( $response, $params );
        }
        echo wp_json_encode( $response );
        wp_die();
    }

    public static function webinarignition_success_response( $params = array() ) {
        $response = array(
            'success' => 1,
            'error'   => 0,
        );
        if ( !empty( $params ) && is_array( $params ) ) {
            $response = array_merge( $response, $params );
        }
        echo wp_json_encode( $response );
        wp_die();
    }

    public static function webinarignition_save_reg_date() {
        // TODO: do something on saving registration date.
    }

    public static function webinarignition_track_video_time() {
        $nonce = ( isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0 );
        if ( !wp_verify_nonce( $nonce, 'limit-custom-video' ) ) {
            die( 'Security violated' );
        }
    }

    public static function webinarignition_track_video_time_iframe() {
        $nonce = ( isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0 );
        if ( !wp_verify_nonce( $nonce, 'limit-iframe-video' ) ) {
            die( 'Security violated' );
        }
    }

    public static function webinarignition_return_video_time_left() {
        $nonce = ( isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0 );
        if ( !wp_verify_nonce( $nonce, 'limit-custom-video' ) ) {
            die( 'Security violated' );
        }
    }

    public static function webinarignition_dismiss_admin_notice() {
        $security = ( isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '' );
        if ( !wp_verify_nonce( $security, 'webinarignition-notice' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            // Send error response
            wp_die();
            // Terminate the script
        }
        // ! TODO: Use nonce verification if possible.
        if ( current_user_can( 'edit_posts' ) ) {
            if ( isset( $_REQUEST['dismiss_wi_notice'] ) ) {
                update_user_meta( get_current_user_id(), 'notice-webinarignition-free', 1 );
                wp_die( 'success' );
            }
        }
        wp_die( 'failed' );
    }

    public static function webinarignition_check_email_is_of_non_subscriber() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
            wp_send_json( array(
                'status' => false,
            ) );
        }
        $email = ( !empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : false );
        $allowed_user = ( !empty( $_POST['user_allowed'] ) ? sanitize_email( $_POST['user_allowed'] ) : false );
        if ( !$email ) {
            wp_send_json( array(
                'status' => true,
            ) );
        }
        $user = get_user_by( 'email', $email );
        $admin = $user && !in_array( 'subscriber', $user->roles, true );
        if ( !$admin ) {
            wp_send_json( array(
                'status' => false,
            ) );
        } else {
            wp_send_json( array(
                'status' => true,
            ) );
        }
    }

    public static function webinarignition_send_email_verification_code() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        global $wpdb;
        $current_locale = ( isset( $_POST['current_locale'] ) ? sanitize_text_field( $_POST['current_locale'] ) : null );
        $input_email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null );
        $app_id = ( isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : null );
        if ( $current_locale ) {
            switch_to_locale( $current_locale );
        }
        if ( empty( $input_email ) || empty( $app_id ) ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.', 'webinar-ignition' ),
            ) );
        }
        $table_db_name = $wpdb->prefix . 'webinarignition_verification';
        $code = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE email = %s", $input_email ), ARRAY_A );
        $gen_code = wp_rand( 100000, 999999 );
        if ( !isset( $code ) ) {
            $wpdb->query( $wpdb->prepare( "INSERT INTO {$table_db_name} (email, code) VALUES (%s, %d)", $input_email, $gen_code ) );
        } else {
            $id = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$table_db_name} WHERE email = %s", $input_email ), ARRAY_A );
            $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET code = %d WHERE id = %d", $gen_code, $id['id'] ) );
        }
        $to = $input_email;
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
        $subj = __( 'Email Verification Code For New Registration', 'webinar-ignition' );
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;
        $emailBody .= WebinarignitionManager::webinarignition_get_webinarignition_email_verification_template();
        $emailBody .= '</html>';
        $emailBody = str_replace( '{VERIFICATION_CODE}', $gen_code, $emailBody );
        restore_previous_locale();
        try {
            wp_mail(
                $to,
                $subj,
                $emailBody,
                $headers
            );
        } catch ( Exception $e ) {
            self::error_response( array(
                'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Error occurs in sending email!.', 'webinar-ignition' ),
            ) );
        }
        self::webinarignition_success_response( array(
            'message' => __( 'Success', 'webinar-ignition' ) . ': ' . __( 'Great lets register.', 'webinar-ignition' ),
        ) );
    }

    public static function webinarignition_verify_user_email() {
        if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid security token',
            ], 403 );
            exit;
        }
        $input_email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null );
        $get_code = ( isset( $_POST['code'] ) ? sanitize_text_field( $_POST['code'] ) : null );
        $skipVerifyEmail = ( isset( $_POST['skipVerifyEmail'] ) ? $_POST['skipVerifyEmail'] : null );
        if ( $skipVerifyEmail ) {
            echo wp_json_encode( array(
                'verified' => 1,
                'status'   => 'success',
            ) );
            wp_die();
        }
        global $wpdb;
        $table_db_name = $wpdb->prefix . 'webinarignition_verification';
        $code = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE email = %s", $input_email ), ARRAY_A );
        if ( isset( $code ) && $code['code'] === $get_code ) {
            $response = array(
                'verified' => 1,
                'status'   => 'success',
            );
        } else {
            $response = array(
                'verified' => 0,
                'status'   => 'failed',
            );
        }
        echo wp_json_encode( $response );
        wp_die();
    }

    public static function webinarignition_check_webinar_before_date( $date ) {
        $webinar_created_timestamp = strtotime( $date );
        $plugin_updated_timestamp = strtotime( get_option( 'webinarignition_plugin_activation_date', gmdate( 'Y-m-d' ) ) );
        // Check if the post was created before the specified date
        return $webinar_created_timestamp < $plugin_updated_timestamp;
    }

    public static function webinarignition_hide_popup_for_free_plan_on_webinar_save() {
        if ( empty( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'webinarignition_ajax_nonce' ) ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid nonce', 'webinar-ignition' ),
            ) );
        }
        $webinar = ( !empty( $_POST['webinar'] ) ? json_decode( wp_unslash( sanitize_text_field( $_POST['webinar'] ) ), true ) : array() );
        if ( empty( $webinar ) ) {
            wp_send_json_success( [
                'popup' => false,
            ] );
        }
        $option_handle = 'webinarignition_webinar_' . absint( $webinar['ID'] ) . '_updated_after_popup';
        add_option( $option_handle, true );
    }

}

add_action( 'wp_ajax_nopriv_webinarignition_add_lead_check_secure', array('WebinarignitionAjax', 'webinarignition_add_lead_check_secure') );
add_action( 'wp_ajax_webinarignition_add_lead_check_secure', array('WebinarignitionAjax', 'webinarignition_add_lead_check_secure') );
add_action( 'wp_ajax_nopriv_webinarignition_register_support', array('WebinarignitionAjax', 'webinarignition_register_support') );
add_action( 'wp_ajax_webinarignition_register_support', array('WebinarignitionAjax', 'webinarignition_register_support') );
add_action( 'wp_ajax_nopriv_webinarignition_submit_chat_question', array('WebinarignitionAjax', 'webinarignition_submit_chat_question') );
add_action( 'wp_ajax_webinarignition_submit_chat_question', array('WebinarignitionAjax', 'webinarignition_submit_chat_question') );
add_action( 'wp_ajax_nopriv_webinarignition_load_chat_messages', array('WebinarignitionAjax', 'webinarignition_load_chat_messages') );
add_action( 'wp_ajax_webinarignition_load_chat_messages', array('WebinarignitionAjax', 'webinarignition_load_chat_messages') );
add_action( 'wp_ajax_nopriv_webinarignition_refresh_chat_messages', array('WebinarignitionAjax', 'webinarignition_refresh_chat_messages') );
add_action( 'wp_ajax_webinarignition_refresh_chat_messages', array('WebinarignitionAjax', 'webinarignition_refresh_chat_messages') );
add_action( 'wp_ajax_nopriv_webinarignition_activate_freemius', array('WebinarignitionAjax', 'webinarignition_activate_freemius') );
add_action( 'wp_ajax_webinarignition_activate_freemius', array('WebinarignitionAjax', 'webinarignition_activate_freemius') );
add_action( 'wp_ajax_nopriv_webinarignition_track_is_live', array('WebinarignitionAjax', 'webinarignition_track_is_live') );
add_action( 'wp_ajax_webinarignition_track_is_live', array('WebinarignitionAjax', 'webinarignition_track_is_live') );
add_action( 'wp_ajax_nopriv_webinarignition_tracking_tags', array('WebinarignitionAjax', 'webinarignition_tracking_tags') );
add_action( 'wp_ajax_webinarignition_tracking_tags', array('WebinarignitionAjax', 'webinarignition_tracking_tags') );
add_action( 'wp_ajax_nopriv_webinarignition_save_reg_date', array('WebinarignitionAjax', 'webinarignition_save_reg_date') );
add_action( 'wp_ajax_webinarignition_webinarignition_save_reg_date', array('WebinarignitionAjax', 'save_reg_date') );
add_action( 'wp_ajax_wi_track_self_hosted_videos_time', array('WebinarignitionAjax', 'webinarignition_track_video_time') );
add_action( 'wp_ajax_nopriv_wi_track_self_hosted_videos_time', array('WebinarignitionAjax', 'webinarignition_track_video_time') );
add_action( 'wp_ajax_wi_track_embeded_videos_time', array('WebinarignitionAjax', 'webinarignition_track_video_time_iframe') );
add_action( 'wp_ajax_nopriv_wi_track_embeded_videos_time', array('WebinarignitionAjax', 'webinarignition_track_video_time_iframe') );
add_action( 'wp_ajax_wi_get_self_hosted_videos_time_left', array('WebinarignitionAjax', 'webinarignition_return_video_time_left') );
add_action( 'wp_ajax_nopriv_wi_get_self_hosted_videos_time_left', array('WebinarignitionAjax', 'webinarignition_return_video_time_left') );
add_action( 'wp_ajax_webinarignition_dismiss_notice', array('WebinarignitionAjax', 'webinarignition_dismiss_admin_notice') );
add_action( 'wp_ajax_webinarignition_send_email_verification_code', array('WebinarignitionAjax', 'webinarignition_send_email_verification_code') );
add_action( 'wp_ajax_nopriv_webinarignition_send_email_verification_code', array('WebinarignitionAjax', 'webinarignition_send_email_verification_code') );
add_action( 'wp_ajax_webinarignition_check_email_is_of_non_subscriber', array('WebinarignitionAjax', 'webinarignition_check_email_is_of_non_subscriber') );
add_action( 'wp_ajax_nopriv_webinarignition_check_email_is_of_non_subscriber', array('WebinarignitionAjax', 'webinarignition_check_email_is_of_non_subscriber') );
add_action( 'wp_ajax_webinarignition_verify_user_email', array('WebinarignitionAjax', 'webinarignition_verify_user_email') );
add_action( 'wp_ajax_nopriv_webinarignition_verify_user_email', array('WebinarignitionAjax', 'webinarignition_verify_user_email') );
add_action( 'wp_ajax_webinarignition/hide_popup_for_free_plan', array('WebinarignitionAjax', 'webinarignition_hide_popup_for_free_plan_on_webinar_save') );