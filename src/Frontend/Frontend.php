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
 * @package     ta-forms
 * @subpackage  ta-forms/src/Frontend
 * @author      ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Frontend;

use ThemeAtelier\TaForms\Helpers\Helpers;

// don't call the file directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Frontend
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

    public $unique_id;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of the plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct()
    {
        $this->unique_id = wp_rand();
        $this->min   = defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
        add_shortcode('ta_forms', array($this, 'ta_forms_shortcode'));
        add_action('wp_ajax_ta_forms_send_email', array($this, 'ta_forms_send_email'));
        add_action('wp_ajax_nopriv_ta_forms_send_email', array($this, 'ta_forms_send_email'));

        add_action('wp_head', array($this, 'ta_forms_header_script'), 1);
        add_action('login_head', array($this, 'ta_forms_header_script'), 1);
        add_action('register_head', array($this, 'ta_forms_header_script'), 1);
    }

    function ta_forms_header_script()
    {
        $options = get_option('ta-forms');
?>
        <script type="text/javascript" class="ta_forms_inline_js">
            // Header Scripts
        </script>
<?php
    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public static function enqueue_scripts()
    {
        wp_enqueue_style('sweetalert2');
        wp_enqueue_style('ico-font');
        wp_enqueue_style('ta-forms-style');
        wp_enqueue_script('sweetalert2');
        wp_enqueue_script('jquery_validate');
        wp_enqueue_script('ta-forms-script');

        wp_localize_script(
            'ta-forms-script',
            'frontend_scripts',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('ta_forms_nonce'),
            )
        );
    }


    /**
     * Handles the email sending process for the "Domain For Sale" form.
     *
     * This function verifies the nonce, validates reCAPTCHA, sanitizes and processes form data,
     * and sends an email to the specified target email address.
     * Returns a JSON response indicating success or error status.
     *
     * @return void
     */
    public function ta_forms_send_email()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ta_forms_nonce')) {
            wp_send_json_error([
                'type'        => 'error',
                'title'       => __('Security Check Failed', 'ta-forms'),
                'description' => __('Nonce verification failed.', 'ta-forms'),
                'okay'        => __('OK', 'ta-forms'),
            ]);
            wp_die();
        }

        // Parse the serialized form data
        parse_str($_POST['data'], $formData);

        // Retrieve necessary form data
        $form_id       = sanitize_text_field($_POST['form_id'] ?? '');
        $contact_form  = get_post_meta($form_id, 'ta-forms', true);
        $form_fields   = $contact_form['form_fields'] ?? '';

        $fields_data = Helpers::fields_data($form_fields, $formData);

        // Sanitize user input
        $name      = sanitize_text_field($formData['ta_forms_full_name'] ?? '');
        $email     = sanitize_email($formData['ta_forms_email'] ?? get_option('admin_email'));
        $subject   = sanitize_text_field($formData['ta_forms_subject'] ?? '');
        $phone     = sanitize_text_field($formData['ta_forms_phone'] ?? '');
        $offer     = sanitize_text_field($formData['ta_forms_offer'] ?? '');
        $proposal  = sanitize_text_field($formData['ta_forms_proposal'] ?? '');
        $date      = date('F j, Y, H:i (h:i A) (\G\M\T O)');
        $ip        = esc_sql(sanitize_text_field($_SERVER['REMOTE_ADDR']));
        $siteURL   = get_site_url();

        $ta_forms_target_mail = $contact_form['submission_email_notification_to'] ?? '';
        $ta_forms_email_template = $contact_form['submission_email_body'] ?? '';

        // Email success/error messages
        $submission_email_subject     = $contact_form['submission_email_subject'] ?? 'A New Proposal is Submitted';
        $ta_forms_success_title       = $contact_form['ta_forms_success_title'] ?? 'Email Sent!';
        $ta_forms_success_description = $contact_form['ta_forms_success_description'] ?? 'Your message has been successfully sent.';
        $ta_forms_error_title         = $contact_form['ta_forms_error_title'] ?? 'Error Sending Email';
        $ta_forms_error_description   = $contact_form['ta_forms_error_description'] ?? 'There was an issue sending your message. Please try again later.';
        $ta_forms_error_okay          = $contact_form['ta_forms_error_okay'] ?? 'Ok';

        $options                 = get_option('ta-forms-opt');
        $ta_forms_recaptcha_error_title      = $options['ta-forms-recaptcha-error-title'] ?? '';
        $ta_forms_recaptcha_error_decription = $options['ta-forms-recaptcha-error-description'] ?? '';
        $ta_forms_recaptcha_error_okay       = $options['ta-forms-recaptcha-error-okay'] ?? '';

        $variables = ['{ta_forms_name}', '{ta_forms_email}', '{ta_forms_subject}', '{ta_forms_proposal}', '{ta_forms_phone}', '{ta_forms_offer}', '{ta_forms_date}', '{ta_forms_ip}', '{ta_forms_siteURL}'];
        $values = [$name, $email, $subject, $proposal, $phone, $offer, $date, $ip, $siteURL];

        // Replace placeholders in the email template
        $email_body = trim(str_replace($variables, $values, $ta_forms_email_template));
        $headers = ['From: ' . $name . ' <' . $email . '>'];

        $recaptcha_validation = Helpers::ta_forms_recaptcha_validation($form_fields);

        // Send email using wp_mail()
        if ($recaptcha_validation) {
            if (wp_mail($ta_forms_target_mail, $submission_email_subject, $email_body, $headers)) {
                // Generate a unique verification token
                $verification_token = wp_generate_uuid4();
                $verification_link  = add_query_arg(['ta_forms_verify_email' => $verification_token], home_url('/verify-email/'));

                global $wpdb;
                $tableUsers = $wpdb->prefix . 'ta_forms_offers';

                // Save user data in database
                $fieldsData = $fields_data['fields_data'];
                $fieldsData = array_merge($fieldsData, [
                    'ta_forms_ip'            => $ip,
                    'ta_forms_verify_email'  => $verification_token, // Store the actual token ✅
                    // 'ta_forms_verify_email'  => 'pending', // Store the actual token ✅
                ]);

                $formats = $fields_data['format'] ?? [];
                $formats = array_merge($formats, ['%s', '%s']); // Ensure correct format

                // $insert = $wpdb->insert($tableUsers, $fieldsData, $formats);

                // Send verification email
                // $verification_subject = __('Verify Your Email', 'ta-forms');
                // $verification_body    = sprintf(
                //     __("Hello %s,\n\nThank you for your proposal regarding %s. Please verify your email by clicking the link below:\n\n%s\n\nBest Regards,\n%s", 'ta-forms'),
                //     $name,
                //     $verification_link,
                //     get_bloginfo('name')
                // );

                // wp_mail($email, $verification_subject, $verification_body, ['From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>']);

                // Handle redirection after saving data
                wp_send_json_success([
                    'type'        => 'success',
                    'title'       => esc_html($ta_forms_success_title),
                    'description' => esc_html($ta_forms_success_description),
                    'redirect'    => '',
                ]);
            } else {
                wp_send_json_error([
                    'type'        => 'error',
                    'title'       => esc_html($ta_forms_error_title),
                    'okay'        => esc_html($ta_forms_error_okay),
                    'description' => esc_html($ta_forms_error_description),
                ]);
            }
        } else {
            wp_send_json_error([
                'type'        => 'error',
                'title'       => esc_html($ta_forms_recaptcha_error_title),
                'okay'        => esc_html($ta_forms_recaptcha_error_okay),
                'description' => esc_html($ta_forms_recaptcha_error_decription),
            ]);
        }

        wp_die();
    }

    /**
     * Renders the Domain For Sale form shortcode.
     *
     * Starts output buffering, calls the HTML form generation function, and returns
     * the generated form HTML as a string.
     *
     * @return string The HTML output of the form.
     */
    public function ta_forms_shortcode($attribute)
    {
        if (empty($attribute['id'])) {
            return;
        }

        $shortcode_id = $attribute['id'];
        $contact_form = get_post_meta($shortcode_id, 'ta-forms', true);
        $elements_options = get_post_meta($shortcode_id, 'ta_form_elements', true);

        ob_start();
        Helpers::ta_forms_html_form_code($contact_form, $elements_options, $shortcode_id);
        return ob_get_clean();
    }
}
