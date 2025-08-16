<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/src/Admin
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Admin;

use ThemeAtelier\TaForms\Admin\Views\Options;
use ThemeAtelier\TaForms\Helpers\Pro_Cron;

/**
 * The admin class
 */
class Admin
{
    /**
     * The slug of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_slug   The slug of this plugin.
     */
    private $plugin_slug;

    /**
     * The min of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $min   The slug of this plugin.
     */
    private $min;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The class constructor.
     *
     * @param string $plugin_slug The slug of the plugin.
     * @param string $version Current version of the plugin.
     */
    function __construct($plugin_slug, $version)
    {
        $this->plugin_slug = $plugin_slug;
        $this->version     = $version;
        $this->min         = defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
        Options::options('ta-forms');
        add_action('admin_menu', array($this, 'add_plugin_page'));
        new Pro_Cron();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public static function enqueue_scripts($hook){}

    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_menu_page(
            esc_html__('Ta Forms', 'ta-forms'),
            esc_html__('Ta Forms', 'ta-forms'),
            'manage_options',
            'ta-forms',
            array($this, 'ta_forms_settings'),
            'dashicons-format-aside',
            6
        );
    }

    /**
     * Options page callback
     */
    public function ta_forms_settings() {}

}
