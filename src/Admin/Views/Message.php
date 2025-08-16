<?php

/**
 * Views class for message options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/Admin/Views/Message
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Admin\Views;

use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

class Message
{

    /**
     * Create Option fields for the setting options.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function options($prefix)
    {
        //
        // Messages section.
        //
        TaForms::createSection(
            $prefix,
            array(
                'title'  => __('Messages', 'ta-forms'),
                'icon'   => 'fa fa-exclamation-triangle',
                'fields' => array(
                    array(
                        'id'         => 'required_notice',
                        'type'       => 'switcher',
                        'title'      => __( 'Required Notice', 'ta-forms' ),
                        'subtitle'   => __( 'Display required notice at top of the form.', 'ta-forms' ),
                        'text_on'    => esc_html__( 'Enabled', 'ta-forms' ),
                        'text_off'   => esc_html__( 'Disabled', 'ta-forms' ),
                        'text_width' => 95,
                        'default'    => true,
                    ),
                    array(
                        'id'         => 'notice_label',
                        'type'       => 'text',
                        'title'      => __( 'Notice Label', 'ta-forms' ),
                        'subtitle'   => __( 'Set a label for the required notice.', 'ta-forms' ),
                        'default'    => __( 'Red asterisk fields are required.', 'ta-forms' ),
                        'dependency' => array( 'required_notice', '==', 'true', true ),
                    ),

                    array(
                        'id'       => 'domain_for_sale_redirect',
                        'type'     => 'select',
                        'title'    => __('Redirect', 'ta-forms'),
                        'subtitle' => __('After successful submission, the page will redirect to if a shortcode or specific page is used.', 'ta-forms'),
                        'options'  => array(
                            'same_page'  => __('Same Page', 'ta-forms'),
                            'to_a_page'  => __('To a page', 'ta-forms'),
                            'custom_url' => __('To a custom URL', 'ta-forms'),
                        ),
                        'default'  => 'same_page',
                    ),
                    array(
                        'id'    => 'ta_forms_success_title',
                        'type'  => 'text',
                        'title' => esc_html__('Form Success Title', 'ta-forms'),
                        "default" => esc_html__("Thank You for your proposal!", 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'same_page'),
                    ),
                    array(
                        'id'    => 'ta_forms_success_description',
                        'type'  => 'text',
                        'title' => esc_html__('Form Success Description', 'ta-forms'),
                        "default" => esc_html__("Your message has already arrived! We will contact you shortly.", 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'same_page'),
                    ),
                    array(
                        'id'    => 'ta_forms_error_title',
                        'type'  => 'text',
                        'title' => esc_html__('Form Error Title', 'ta-forms'),
                        "default" => esc_html__("Email not submitted.", 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'same_page'),
                    ),
                    array(
                        'id'    => 'ta_forms_error_description',
                        'type'  => 'text',
                        'title' => esc_html__('Form Error Description', 'ta-forms'),
                        "default" => esc_html__("There might be an error with server instead please send us a direct message at: info@yourdomain.com", 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'same_page'),
                    ),
                    array(
                        'id'    => 'ta_forms_error_okay',
                        'type'  => 'text',
                        'title' => esc_html__('Form Okay Button Value', 'ta-forms'),
                        "default" => esc_html__("Okay", 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'same_page'),
                    ),
                    array(
                        'id'         => 'domain_for_sale_redirect_to_page',
                        'type'       => 'select',
                        'title'      => __('Page', 'ta-forms'),
                        'subtitle'   => __('Select redirect page.', 'ta-forms'),
                        'options'    => 'pages',
                        'dependency' => array('domain_for_sale_redirect', '==', 'to_a_page'),
                    ),
                    array(
                        'id'         => 'domain_for_sale_redirect_custom_url',
                        'type'       => 'text',
                        'title'      => __('Custom URL', 'ta-forms'),
                        'subtitle'   => __('Type redirect custom url.', 'ta-forms'),
                        'dependency' => array('domain_for_sale_redirect', '==', 'custom_url'),
                    ),
                ),
            )
        );
    }
}
