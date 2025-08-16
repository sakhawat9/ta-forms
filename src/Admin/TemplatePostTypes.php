<?php

namespace ThemeAtelier\TaForms\Admin;

use ThemeAtelier\TaForms\Helpers\Helpers;


if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * Custom post class to register the carousel.
 */
class TemplatePostTypes
{

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.2.0
	 */
	private static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.2.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 2.2.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Domain For Sale post type
	 */
	public function register_ta_forms_post_type()
	{
		if (post_type_exists('ta_form')) {
			return;
		}

		$capability = Helpers::ta_forms_dashboard_capability();
		/**
		 * Domain For Sale Form (Under "Domain For Sale" menu)
		 */
		$show_ui = current_user_can($capability) ? true : false;

		register_post_type(
			'ta-forms',
			array(
				'label'               => __('Forms', 'ta-forms'),
				'description'         => __('Forms', 'ta-forms'),
				'public'              => false,
				'has_archive'         => false,
				'publicaly_queryable' => false,
				'show_ui'             => $show_ui,
				'show_in_menu'        => 'ta-forms', // Attach to Parent Menu
				'hierarchical'        => false,
				'query_var'           => false,
				'supports'            => array('title'),
				'capability_type'     => 'post',
				'labels'              => array(
					'name'               => __('Forms', 'ta-forms'),
					'singular_name'      => __('Form', 'ta-forms'),
					'menu_name'          => __('Forms', 'ta-forms'),
					'add_new'            => __('Add New', 'ta-forms'),
					'add_new_item'       => __('Add New Form', 'ta-forms'),
					'edit_item'          => __('Edit Form', 'ta-forms'),
					'search_items'       => __('Search Forms', 'ta-forms'),
					'not_found'          => __('No Forms Found', 'ta-forms'),
				),
			)
		);
	}
}
