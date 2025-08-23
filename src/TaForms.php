<?php

/**
 * The file of the TaForms class.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package TaForms
 */

namespace ThemeAtelier\TaForms;

use ThemeAtelier\TaForms\Loader;
use ThemeAtelier\TaForms\Helpers\Helpers;
use ThemeAtelier\TaForms\Admin\Admin;
use ThemeAtelier\TaForms\Frontend\Frontend;
use ThemeAtelier\TaForms\Admin\TemplatePostTypes;



// don't call the file directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * The main class of the plugin.
 *
 * Handle all the class and methods of the plugin.
 *
 * @author     ThemeAtelier <themeatelierbd@gmail.com>
 */
class TaForms
{

	/**
	 * Plugin version
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var string
	 */
	protected $version;

	/**
	 * Plugin slug
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Main Loader.
	 *
	 * The loader that's responsible for maintaining and registering all hooks that empowers
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var object
	 */
	protected $loader;
	/**
	 * Constructor for the TaForms class.
	 *
	 * Sets up all the appropriate hooks and actions within the plugin.
	 */
	public function __construct()
	{
		$this->version     = TAFORMS_VERSION;
		$this->plugin_slug = 'ta-forms';
		$this->load_dependencies();
		$this->define_constants();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_common_hooks();
		add_action('plugins_loaded', array($this, 'ta_forms_load_textdomain'));
		add_action('plugin_loaded', array($this, 'init_plugin'));

		$active_plugins = get_option('active_plugins');
		foreach ($active_plugins as $active_plugin) {
			$_temp = strpos($active_plugin, 'ta-forms.php');
			if (false !== $_temp) {
				add_filter('plugin_action_links_' . $active_plugin, array($this, 'ta_forms_action_links'));
			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The slug of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_slug()
	{
		return $this->plugin_slug;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

	// load text domain from plugin folder
	function ta_forms_load_textdomain()
	{
		load_plugin_textdomain('ta-forms', false, TAFORMS_DIRNAME . '/languages');
	}

	/**
	 * Define the constants
	 *
	 * @return void
	 */
	public function define_constants()
	{
		define('TAFORMS_URL', plugins_url('', TAFORMS_FILE));
		define('TAFORMS_ASSETS', TAFORMS_URL . '/src/assets/');
	}

	/**
	 * Load the plugin after all plugins are loaded.
	 *
	 * @return void
	 */
	public function init_plugin()
	{
		do_action('ta_forms_loaded');
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - Teamproi18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Frontend. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		$this->loader = new Loader();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public    = new Frontend($this->get_plugin_slug(), $this->get_version());
		$plugin_helpers   = new Helpers($this->get_plugin_slug(), $this->get_version());

		$this->loader->add_action('wp_loaded', $plugin_helpers, 'register_all_scripts');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Register common hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_common_hooks()
	{
		$common_hooks = new TemplatePostTypes();
		$this->loader->add_action('init', $common_hooks, 'register_ta_forms_post_type', 10);
	}

	/**
	 * Register all of the hooks related to the admin dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin   = new Admin($this->get_plugin_slug(), $this->get_version());
		$plugin_helpers = new Helpers($this->get_plugin_slug(), $this->get_version());
		$this->loader->add_action('wp_loaded', $plugin_helpers, 'register_all_scripts');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_filter('manage_ta-forms_posts_columns', $plugin_admin, 'filter_forms_admin_column');
		$this->loader->add_action('manage_ta-forms_posts_custom_column', $plugin_admin, 'display_forms_admin_fields', 10, 2);
	}

	// Plugin settings in plugin list
	public function ta_forms_action_links(array $links)
	{
		$url           = get_admin_url() . 'admin.php?page=ta-forms';
		$settings_link = '<a href="' . esc_url($url) . '">' . esc_html__('Settings', 'ta-forms') . '</a>';
		$links[]       = $settings_link;
		return $links;
	}
}
