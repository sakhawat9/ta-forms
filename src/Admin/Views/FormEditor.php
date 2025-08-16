<?php

/**
 * Views class for Form Editor options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/Admin/Views/FormEditor
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Admin\Views;

use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

class FormEditor
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
        // Form Editor section.
        //
        TaForms::createSection(
            $prefix,
            array(
                'title'  => esc_html__('Form Editor', 'ta-forms'),
                'icon'   => 'fa fa-align-justify',
                'fields' => array(
                    array(
                        'id'     => 'form_fields',
                        'class'  => 'form_fields',
                        'type'   => 'sortable',
                        'fields' => array(
                            array(
                                'id'         => 'full_name',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Full Name', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('Full Name', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('What is your full name?', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => true,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'email_address',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('E-mail Address', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('E-mail Address', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('What is your e-mail address?', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => true,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'subject',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Subject', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('Subject', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('What is your subject?', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => true,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'phone_mobile',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Phone or Mobile', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('Phone or Mobile', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('What is your phone number?', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => false,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'offer',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Offer', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('Offer', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('What is your offer?', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => false,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'proposal',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Proposal Message', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('To hide this label, leave it empty.', 'ta-forms'),
                                                'default' => __('Proposal Message', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'placeholder',
                                                'type'    => 'text',
                                                'title'   => __('Placeholder', 'ta-forms'),
                                                'default' => __('Proposal Message', 'ta-forms'),
                                            ),
                                            array(
                                                'id'      => 'required',
                                                'type'    => 'checkbox',
                                                'title'   => __('Required', 'ta-forms'),
                                                'default' => false,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id'         => 'submit_btn',
                                'type'       => 'accordion',
                                'accordions' => array(
                                    array(
                                        'title'  => __('Submit Button', 'ta-forms'),
                                        'fields' => array(
                                            array(
                                                'id'      => 'label',
                                                'type'    => 'text',
                                                'title'   => __('Label', 'ta-forms'),
                                                'desc'    => __('Type submit button label.', 'ta-forms'),
                                                'default' => __('Submit Testimonial', 'ta-forms'),
                                            ),
                                        ),
                                    ),
                                ),
                            ),

                        ),
                    ),
                ),
            )
        );

        //
        // Metabox of the testimonial form generator.
        // Set a unique slug-like ID.
        //
        $prefix_form_fields = 'ta_forms_form_elements_options';

        //
        // Form metabox.
        //
        TaForms::createMetabox(
            $prefix_form_fields,
            array(
                'title'           => __('Form Fields', 'ta-forms'),
                'post_type'       => 'ta-forms',
                'context'         => 'side',
                'enqueue_webfont' => false,
            )
        );

        // //
        // // Form Editor section.
        // //
        // TaForms::createSection(
        //     $prefix_form_fields,
        //     array(
        //         'fields' => array(

        //             array(
        //                 'id'      => 'form_elements',
        //                 'type'    => 'checkbox',
        //                 'options' => array(
        //                     'name'              => __('Full Name', 'ta-forms'),
        //                     'email'             => __('E-mail Address', 'ta-forms'),
        //                     'subject'             => __('Subject', 'ta-forms'),
        //                     'phone_mobile'      => __('Phone or Mobile', 'ta-forms'),
        //                     'offer'             => __('Offer', 'ta-forms'),
        //                     'proposal'          => __('Proposal', 'ta-forms'),
        //                     // 'agree_checkbox'    => __('Checkbox', 'ta-forms'),
        //                     'recaptcha'         => __('reCAPTCHA', 'ta-forms'),
        //                 ),
        //                 'default' => array('name', 'email', 'offer', 'proposal'),
        //             ),

        //         ),
        //     )
        // );
    }
}
