<?php

/*
Plugin Name: 	Ta Forms
Plugin URI: 	https://wpchathelp.com/
Description: 	ThemeAtelier Forms.
Author: 		ThemeAtelier
Version: 		1.0.1
Author:         ThemeAtelier
Author URI:     https://themeatelier.net/
License:        GPL-2.0+
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
Requirements:   PHP 7.0 or above, WordPress 4.0 or above.
Text Domain:    ta-forms
Domain Path:    /languages
*/

// Block Direct access
if (!defined('ABSPATH')) {
    die('You should not access this file directly!');
}
require_once __DIR__ . '/vendor/autoload.php';

use ThemeAtelier\TaForms\TaForms;

define('TAFORMS_VERSION', '1.0.1');
define('TAFORMS_FILE', __FILE__);
define('TAFORMS_DIRNAME', dirname(__FILE__));
define('TAFORMS_DIR_PATH', plugin_dir_path(__FILE__));
define('TAFORMS_DIR_URL', plugin_dir_url(__FILE__));
define('TAFORMS_BASENAME', plugin_basename(__FILE__));
define('TAFORMS_STORE_URL', 'https://themeatelier.net');
define('TAFORMS_PRODUCT_URL', 'https://themeatelier.net/downloads/whatsapp-ta-forms');
define('TAFORMS_ITEM_ID',  534);
define('TAFORMS_ITEM_NAME', 'Ta Forms Help');
define('TAFORMS_ITEM_SLUG', 'whatsapp-ta-forms');
define('TAFORMS_DEMO_URL', 'https://wpchathelp.com/');

function ta_forms_run()
{
    // Launch the plugin.
    $TaForms = new TaForms();
    $TaForms->run();
}
add_action('after_setup_theme', 'ta_forms_run');

add_action('activated_plugin', 'ta_forms_redirect_to');
function ta_forms_redirect_to($plugin)
{
    if (TAFORMS_BASENAME === $plugin) {
        $redirect_url = esc_url(admin_url('edit.php?post_type=ta-forms'));
        exit(wp_kses_post(wp_safe_redirect($redirect_url)));
    }
}
