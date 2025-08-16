<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/src/Helpers
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Helpers;

/**
 * The Helpers class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Helpers
{

	/**
	 * The min of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $min   The slug of this plugin.
	 */
	private $min;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct()
	{
		$this->min = defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts()
	{
		wp_register_style('sweetalert2', TAFORMS_ASSETS . 'css/sweetalert2' . $this->min . '.css', array(), '1.0.0', 'all');
		wp_register_style('ico-font', TAFORMS_ASSETS . 'css/icofont' . $this->min . '.css', array(), '1.0.0', 'all');
		wp_register_style('ta-forms-style', TAFORMS_ASSETS . 'css/ta-forms-style' . $this->min . '.css', array(), TAFORMS_VERSION, 'all');

		wp_register_script('sweetalert2', TAFORMS_ASSETS . 'js/sweetalert2' . $this->min . '.js', array('jquery'), TAFORMS_VERSION, true);
		wp_register_script('jquery_validate', TAFORMS_ASSETS . 'js/jquery.validate' . $this->min . '.js', array('jquery'), TAFORMS_VERSION, true);
		wp_register_script('ta-forms-script', TAFORMS_ASSETS . 'js/ta-forms-script' . $this->min . '.js', array('jquery'), TAFORMS_VERSION, true);
	}
	/**
	 * Custom Template locator .
	 *
	 * @param  mixed $template_name template name .
	 * @param  mixed $template_path template path .
	 * @param  mixed $default_path default path .
	 * @return string
	 */
	public static function ta_forms_locate_template($template_name, $default_path = '')
	{
		if (!$default_path) {
			$default_path = TAFORMS_DIR_PATH . 'src/Frontend/Templates/';
		}
		$template = locate_template($template_name);
		// Get default template.
		if (!$template) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Ta Forms dashboard capability.
	 *
	 * Returns the capability required to access the Ta Forms dashboard.
	 *
	 * @return string The required capability for the dashboard.
	 */
	public static function ta_forms_dashboard_capability()
	{
		return apply_filters('ta_forms_dashboard_capability', 'manage_options');
	}

	/**
	 * Validates the reCAPTCHA response for form submissions.
	 *
	 * Checks the reCAPTCHA response using Google's API and returns validation status.
	 *
	 * @return bool True if validation passed, false otherwise.
	 */
	public static function ta_forms_recaptcha_validation($form_editor)
	{
		$recaptcha = false;
		foreach ($form_editor as $field_id => $form_field) {
			$field_name = isset($form_field['field_select']) ? $form_field['field_select'] : '';

			switch ($field_name) {
				case 'recaptcha':
					$recaptcha = true;
					break;
			}
		}
		if ($recaptcha) {
			$options                 = get_option('ta-forms-opt');
			$ta_forms_recaptcha_version   = !empty($options['ta_forms_recaptcha_version']) ? $options['ta_forms_recaptcha_version'] : '';
			$recaptcha_secretkey_v2  = !empty($options['ta-forms-recaptcha-secretkey']) ? $options['ta-forms-recaptcha-secretkey'] : '';
			$recaptcha_secretkey_v3  = !empty($options['ta-forms-recaptcha-secretkey_v3']) ? $options['ta-forms-recaptcha-secretkey_v3'] : '';

			if ($recaptcha_secretkey_v2 || $recaptcha_secretkey_v3) {
				parse_str($_POST['data'], $formData);
				$g_recaptcha_response = isset($formData['g-recaptcha-response']) ? $formData['g-recaptcha-response'] : '';

				$token = isset($formData['token']) ? $formData['token'] : '';
				$remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
				$response         = ('v2' === $ta_forms_recaptcha_version) ? $g_recaptcha_response ?? '' : $token ?? '';
				$recaptcha_secret = ('v2' === $ta_forms_recaptcha_version) ? $recaptcha_secretkey_v2 : $recaptcha_secretkey_v3;
				$remote_ip        = $remote_addr;

				// Send request to Google's reCAPTCHA verification API
				$recaptcha_response = wp_remote_post(
					'https://www.google.com/recaptcha/api/siteverify',
					array(
						'body' => array(
							'secret'   => $recaptcha_secret,
							'response' => $response,
							'remoteip' => $remote_ip,
						),
					)
				);

				$recaptcha_data = json_decode(wp_remote_retrieve_body($recaptcha_response));
				return !empty($recaptcha_data->success);
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	public static function fields_data($form_fields, $formData)
	{
		// Initialize an array to store field values
		$fields_data = [];
		$format = [];
		$field_index = 1;

		foreach ($form_fields as $field_id => $form_field) {
			switch ($field_id) {
				case 'full_name':
					$fields_data['ta_forms_name'] = sanitize_text_field($formData['ta_forms_full_name'] ?? '');
					$format[] = '%s';
					break;
				case 'email_address':
					$fields_data['ta_forms_email'] = sanitize_email($formData['ta_forms_email'] ?? '');
					$format[] = '%s';
					break;
				case 'subject':
					$fields_data['ta_forms_subject'] = sanitize_text_field($formData['ta_forms_subject'] ?? '');
					$format[] = '%s';
					break;
				case 'phone_mobile':
					$fields_data['ta_forms_phone'] = sanitize_text_field($formData['ta_forms_phone'] ?? '');
					$format[] = '%s';
					break;
				case 'offer':
					$fields_data['ta_forms_offer'] = sanitize_text_field($formData['ta_forms_offer'] ?? '');
					$format[] = '%s';
					break;
				case 'proposal':
					$fields_data['ta_forms_proposal'] = sanitize_textarea_field($formData['ta_forms_proposal'] ?? '');
					$format[] = '%s';
					break;
			}

			$field_index++;
		}

		return [
			'fields_data' => $fields_data,
			'format' => $format
		];
	}

	/**
	 * Generates the HTML form for the Ta Forms plugin.
	 *
	 * Outputs the HTML code for the Domain For Sale form, with dynamic labels, placeholders, and reCAPTCHA support if enabled.
	 *
	 * @return void
	 */
	public static function ta_forms_html_form_code($form_options, $elements_options, $form_id)
	{
		$form_element = isset($elements_options['form_elements']) ? $elements_options['form_elements'] : array();

		$form_fields = isset($form_options['form_fields']) ? $form_options['form_fields'] : [];
		$full_name                  = isset($form_fields['full_name']) ? $form_fields['full_name'] : '';
		$full_name_required         = isset($full_name['required']) ? 'required' : '';
		$email_address              = isset($form_fields['email_address']) ? $form_fields['email_address'] : '';
		$email_address_required     = isset($email_address['required']) ? 'required' : '';
		$subject              		= isset($form_fields['subject']) ? $form_fields['subject'] : '';
		$subject_required     		= isset($subject['required']) ? 'required' : '';
		$phone_mobile               = isset($form_fields['phone_mobile']) ? $form_fields['phone_mobile'] : '';
		$phone_mobile_required      = isset($phone_mobile['required']) ? 'required' : '';
		$recaptcha                  = isset($form_fields['recaptcha']) ? $form_fields['recaptcha'] : '';
		$submit_btn                 = isset($form_fields['submit_btn']) ? $form_fields['submit_btn'] : '';


		// if ($form_fields) {
		$required_notice = isset($form_options['required_notice']) ? $form_options['required_notice'] : '';
		$notice_label = isset($form_options['notice_label']) ? $form_options['notice_label'] : '';
		$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
		if (!is_admin() && $required_notice && !empty($notice_label)) {
			echo '<div class="testimonial-required-message">' . esc_html($notice_label) . '</div>';
		}
		echo '<div class="ta_forms"><form class="form" data-form_id="' . esc_attr($form_id) . '" action="' . esc_url($request_uri) . '" method="post">';
		if (is_admin()) {
			if ($required_notice && !empty($notice_label)) {
				echo '<div class="testimonial-required-message">' . esc_html($notice_label) . '</div>';
			}
		}

		foreach ($form_fields as $field_id => $form_field) {
			switch ($field_id) {
				case 'full_name':
					if (in_array('name', $form_element, true)) {
						include self::ta_forms_locate_template('form/name.php');
					}
					break;
				case 'email_address':
					if (in_array('email', $form_element, true)) {
						include self::ta_forms_locate_template('form/email.php');
					}
					break;
				case 'subject':
					if (in_array('subject', $form_element, true)) {
						include self::ta_forms_locate_template('form/subject.php');
					}
					break;
				case 'phone_mobile':
					if (in_array('phone_mobile', $form_element, true)) {
						include self::ta_forms_locate_template('form/number.php');
					}
					break;
				case 'offer':
					if (in_array('offer', $form_element, true)) {
						include self::ta_forms_locate_template('form/offer.php');
					}
					break;
				case 'proposal':
					if (in_array('proposal', $form_element, true)) {
						include self::ta_forms_locate_template('form/proposal.php');
					}
					break;
				case 'agree_checkbox':
					if (in_array('agree_checkbox', $form_element, true)) {
						include self::ta_forms_locate_template('form/checkbox.php');
					}
					break;
				case 'recaptcha':
					if (in_array('recaptcha', $form_element, true)) {
						include self::ta_forms_locate_template('form/recaptcha.php');
					}
					break;
				case 'submit_btn':
					include self::ta_forms_locate_template('form/submit.php');
					break;
			}
		}
		echo '</form></div>';
	}
}
