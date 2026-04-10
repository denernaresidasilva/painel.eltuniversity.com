<?php
/**
 * Elementor Form Widget — lead capture form.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Elementor_Form_Widget extends \Elementor\Widget_Base {

    public function get_name(): string {
        return 'wpla_form';
    }

    public function get_title(): string {
        return __( 'LC CRM Form', 'lc-crm' );
    }

    public function get_icon(): string {
        return 'eicon-form-horizontal';
    }

    public function get_categories(): array {
        return array( 'general' );
    }

    public function get_keywords(): array {
        return array( 'form', 'crm', 'lead', 'subscribe', 'lc' );
    }

    protected function register_controls(): void {
        // Content section.
        $this->start_controls_section( 'content_section', array(
            'label' => __( 'Form Settings', 'lc-crm' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        // List selector.
        $lists   = WPLA_List_Model::all();
        $options = array( 0 => __( '— Select List —', 'lc-crm' ) );
        foreach ( $lists as $list ) {
            $options[ $list->id ] = $list->name;
        }

        $this->add_control( 'list_id', array(
            'label'   => __( 'List', 'lc-crm' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => $options,
            'default' => 0,
        ) );

        $this->add_control( 'form_title', array(
            'label'   => __( 'Title', 'lc-crm' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Subscribe to our list', 'lc-crm' ),
        ) );

        $this->add_control( 'button_text', array(
            'label'   => __( 'Button Text', 'lc-crm' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Subscribe', 'lc-crm' ),
        ) );

        $this->end_controls_section();

        // Style section.
        $this->start_controls_section( 'style_section', array(
            'label' => __( 'Form Style', 'lc-crm' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control( 'button_color', array(
            'label'     => __( 'Button Color', 'lc-crm' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => array(
                '{{WRAPPER}} .wpla-submit' => 'background-color: {{VALUE}};',
            ),
        ) );

        $this->add_control( 'border_radius', array(
            'label'      => __( 'Border Radius', 'lc-crm' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array( 'px' ),
            'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
            'default'    => array( 'size' => 8, 'unit' => 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} .wpla-form input, {{WRAPPER}} .wpla-submit' => 'border-radius: {{SIZE}}{{UNIT}};',
            ),
        ) );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        echo do_shortcode( sprintf(
            '[wpla_form list="%d" title="%s" button_text="%s"]',
            absint( $settings['list_id'] ),
            esc_attr( $settings['form_title'] ),
            esc_attr( $settings['button_text'] )
        ) );
    }

    protected function content_template(): void {
        ?>
        <div class="wpla-form-wrapper">
            <h3 class="wpla-form-title">{{{ settings.form_title }}}</h3>
            <div style="padding:20px;background:#f9fafb;border-radius:8px;text-align:center;">
                <p style="color:#6b7280;">📋 LC CRM Form — List #{{{ settings.list_id }}}</p>
                <button style="background:#6366f1;color:#fff;padding:10px 24px;border:none;border-radius:8px;cursor:pointer;">{{{ settings.button_text }}}</button>
            </div>
        </div>
        <?php
    }
}
