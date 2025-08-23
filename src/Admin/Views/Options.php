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
        TaForms::createMetabox(
            $prefix,
            array(
                'title'            => esc_html__('Ta Forms', 'ta-forms'),
                'post_type'        => 'ta-forms',
                'show_restore'     => false,
                'nav'              => 'inline',
                'theme'            => 'light',
            )
        );
        FormEditor::options($prefix);
        Message::options($prefix);
        Notifications::options($prefix);
    }
}
