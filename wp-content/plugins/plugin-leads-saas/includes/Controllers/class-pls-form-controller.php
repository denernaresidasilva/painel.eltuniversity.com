<?php
/**
 * Form Controller — handles form rendering and submission.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PLS_Form_Controller {

    public function __construct() {
        add_shortcode( 'pls_form', array( $this, 'render_shortcode' ) );
        add_action( 'wp_ajax_pls_form_submit', array( $this, 'handle_submit' ) );
        add_action( 'wp_ajax_nopriv_pls_form_submit', array( $this, 'handle_submit' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * Enqueue frontend form assets.
     */
    public function enqueue_assets(): void {
        wp_register_style( 'pls-forms', PLS_PLUGIN_URL . 'assets/css/forms.css', array(), PLS_VERSION );
        wp_register_script( 'pls-forms', PLS_PLUGIN_URL . 'assets/js/forms.js', array(), PLS_VERSION, true );
        wp_localize_script( 'pls-forms', 'pls_form_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'pls_form_nonce' ),
        ) );
    }

    /**
     * Render [pls_form list="ID"] shortcode.
     */
    public function render_shortcode( $atts ): string {
        $atts = shortcode_atts( array(
            'list'        => 0,
            'title'       => '',
            'button_text' => __( 'Subscribe', 'plugin-leads-saas' ),
            'class'       => '',
        ), $atts, 'pls_form' );

        $list_id = absint( $atts['list'] );
        $list    = $list_id ? PLS_List_Model::get( $list_id ) : null;

        // Default form fields.
        $fields = array(
            array( 'name' => 'email', 'label' => __( 'Email', 'plugin-leads-saas' ), 'type' => 'email', 'required' => true ),
            array( 'name' => 'first_name', 'label' => __( 'First Name', 'plugin-leads-saas' ), 'type' => 'text', 'required' => false ),
            array( 'name' => 'last_name', 'label' => __( 'Last Name', 'plugin-leads-saas' ), 'type' => 'text', 'required' => false ),
            array( 'name' => 'phone', 'label' => __( 'Phone', 'plugin-leads-saas' ), 'type' => 'tel', 'required' => false ),
        );

        if ( $list && ! empty( $list->form_fields ) ) {
            $custom = json_decode( $list->form_fields, true );
            if ( is_array( $custom ) && ! empty( $custom ) ) {
                $fields = $custom;
            }
        }

        wp_enqueue_style( 'pls-forms' );
        wp_enqueue_script( 'pls-forms' );

        $extra_class = sanitize_html_class( $atts['class'] );
        $title       = esc_html( $atts['title'] ?: ( $list ? $list->name : '' ) );

        ob_start();
        ?>
        <div class="pls-form-wrapper <?php echo $extra_class; ?>">
            <?php if ( $title ) : ?>
                <h3 class="pls-form-title"><?php echo $title; ?></h3>
            <?php endif; ?>
            <form class="pls-form" data-list="<?php echo esc_attr( $list_id ); ?>">
                <?php foreach ( $fields as $field ) :
                    $fname    = sanitize_key( $field['name'] );
                    $flabel   = esc_html( $field['label'] );
                    $ftype    = esc_attr( $field['type'] ?? 'text' );
                    $freq     = ! empty( $field['required'] );
                    ?>
                    <div class="pls-field">
                        <label for="pls-<?php echo $fname; ?>"><?php echo $flabel; ?></label>
                        <input
                            type="<?php echo $ftype; ?>"
                            id="pls-<?php echo $fname; ?>"
                            name="<?php echo $fname; ?>"
                            <?php echo $freq ? 'required' : ''; ?>
                            placeholder="<?php echo $flabel; ?>"
                        />
                    </div>
                <?php endforeach; ?>

                <!-- Hidden UTM fields -->
                <input type="hidden" name="utm_source" class="pls-utm" />
                <input type="hidden" name="utm_medium" class="pls-utm" />
                <input type="hidden" name="utm_campaign" class="pls-utm" />
                <input type="hidden" name="utm_content" class="pls-utm" />
                <input type="hidden" name="utm_term" class="pls-utm" />
                <input type="hidden" name="list_id" value="<?php echo esc_attr( $list_id ); ?>" />

                <button type="submit" class="pls-submit">
                    <?php echo esc_html( $atts['button_text'] ); ?>
                </button>
                <div class="pls-form-message" style="display:none;"></div>
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
        if ( ! check_ajax_referer( 'pls_form_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'plugin-leads-saas' ) ), 403 );
        }

        // Rate limiting.
        $ip    = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
        $trans = 'pls_rate_' . md5( $ip );
        $count = (int) get_transient( $trans );
        if ( $count >= 10 ) {
            wp_send_json_error( array( 'message' => __( 'Too many submissions. Please try again later.', 'plugin-leads-saas' ) ), 429 );
        }
        set_transient( $trans, $count + 1, 60 );

        $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        if ( ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid email.', 'plugin-leads-saas' ) ) );
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

        $result = PLS_Contact::create( $data );

        if ( is_wp_error( $result ) ) {
            // If duplicate, get existing contact.
            if ( 'duplicate_email' === $result->get_error_code() ) {
                $err_data   = $result->get_error_data();
                $contact_id = $err_data['contact_id'] ?? 0;
                if ( $contact_id ) {
                    PLS_Contact::update( $contact_id, $data );
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
            PLS_Contact::subscribe_list( (int) $contact_id, $list_id );
        }

        // Fire form_submitted event.
        do_action( 'pls_event', 'form_submitted', (int) $contact_id, array(
            'list_id' => $list_id,
            'source'  => 'form',
        ) );

        wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'plugin-leads-saas' ) ) );
    }
}
