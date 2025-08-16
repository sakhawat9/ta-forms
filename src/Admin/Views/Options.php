<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/src/Admin/Views/Options
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Admin\Views;

use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

class Options
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
        // Create options
        // TaForms::createOptions($prefix, array(
        //     'menu_title'        => esc_html__('Ta Forms Help', 'ta-forms'),
        //     'menu_slug'         => 'ta-forms',
        //     'menu_type'               => 'submenu',
        //     'show_search'             => false,
        //     'show_bar_menu'           => false,
        //     'show_sub_menu'           => false,
        //     'show_reset_all'          => false,
        //     'show_footer'			  => false,
        //     'show_all_options'		  => false,
        //     'framework_title'   => esc_html__('Ta Forms Help', 'ta-forms'),
        //     'admin_bar_menu_priority' => 5,
        //     'footer_text'             => esc_html__('Thank you for using our product', 'ta-forms'),
        //     'theme'                   => 'light',
        //     'nav'                     => 'inline',
        //     'framework_class'         => 'ta-forms-admin',
        //     'class'              	  => 'ta-forms-preloader',
        // ));

        TaForms::createMetabox(
            $prefix,
            array(
                'title'            => esc_html__('Ta Forms', 'ta-forms'),
                'post_type'        => 'ta-forms',
                'show_restore'     => false,
                'nav'            => 'inline',
                'theme'            => 'light',
            )
        );
        FormEditor::options($prefix);
        Message::options($prefix);
        Notifications::options($prefix);
    }
}
