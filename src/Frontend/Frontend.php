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

        add_action('wp_head', array($this, 'ta_forms_header_script'), 1);
        add_action('login_head', array($this, 'ta_forms_header_script'), 1);
        add_action('register_head', array($this, 'ta_forms_header_script'), 1);
    }

    function ta_forms_header_script()
    {
        $options = get_option('cwp_option');
        $alternative_wHelpBubble = isset($options['alternative_wHelpBubble']) ? $options['alternative_wHelpBubble'] : "";
?>
        <script type="text/javascript" class="ta_forms_inline_js">
            var alternativeWHelpBubble = "<?php echo esc_attr($alternative_wHelpBubble); ?>";
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
        $options = get_option('cwp_option');
        $open_in_new_tab         = isset($options['open_in_new_tab']) ? $options['open_in_new_tab'] : '';
        $open_in_new_tab         = $open_in_new_tab ? '_blank' : '_self';

        wp_enqueue_style('ico-font');
        wp_enqueue_style('ta-forms-style');
        wp_enqueue_script('ta-forms-script');

        wp_localize_script(
            'ta-forms-script',
            'frontend_scripts',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('ta_forms_nonce'),
            )
        );
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
        
        echo '<pre>'; print_r($shortcode_id); echo '</pre>';
        $elements_options = get_post_meta($shortcode_id, 'ta_forms_form_elements_options', true);

        ob_start();
        Helpers::ta_forms_html_form_code($contact_form, $elements_options, $shortcode_id);
        return ob_get_clean();
    }
}
