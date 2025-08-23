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

use ThemeAtelier\TaForms\Admin\Offers\OffersView;
use ThemeAtelier\TaForms\Admin\Views\Options;
use ThemeAtelier\TaForms\Helpers\Helpers;
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

        add_action('admin_head', array($this, 'react_preamble'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 100);

        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if (in_array($handle, ['vite-client', 'ta-forms-admin'])) {
                return '<script type="module" src="' . esc_url($src) . '"></script>';
            }
            return $tag;
        }, 10, 3);

        add_action('init', [$this, 'add_cors_header']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    /**
     * Add forms admin columns.
     *
     * @since 2.0.0
     * @return statement
     */
    public function filter_forms_admin_column()
    {

        $admin_columns['cb']         = '<input type="checkbox" />';
        $admin_columns['title']      = esc_html__('Title', 'ta-forms');
        $admin_columns['shortcode']  = esc_html__('Shortcode', 'ta-forms');
        $admin_columns['date']       = esc_html__('Date', 'ta-forms');

        return $admin_columns;
    }

    /**
     * Display admin columns for the forms.
     *
     * @param mix    $column The columns.
     * @param string $post_id The post ID.
     * @return void
     */
    public function display_forms_admin_fields($column, $post_id)
    {
        switch ($column) {
            case 'shortcode':
                $column_field = '<input  class="ta_forms_input" style="width: 230px;padding: 4px 8px;cursor: pointer;" type="text" onClick="this.select();" readonly="readonly" value="[ta_forms id=&quot;' . esc_attr($post_id) . '&quot;]"/> <div class="ta-forms-after-copy-text"><i class="icofont-check-circled"></i> ' . esc_html('Shortcode Copied to Clipboard!', 'ta-forms') . ' </div>';

                $allowed_tags = array(
                    'input' => array(
                        'class' => true,
                        'style' => true,
                        'type' => true,
                        'onclick' => true,
                        'readonly' => true,
                        'value' => true,
                    ),
                    'div' => array(
                        'class' => true,
                    ),
                    'i' => array(
                        'class' => true,
                    ),
                );

                // Output with KSES sanitization
                echo wp_kses($column_field, $allowed_tags);
                break;
        } // end switch.
    }

    public function register_rest_routes()
    {
        register_rest_route(
            'ta-forms/v1',
            '/offers',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_offers'],
                'permission_callback' => '__return_true', // make it public (you can restrict later)
            ]
        );
        register_rest_route(
            'ta-forms/v1',
            '/offers/(?P<id>\d+)',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_offer_by_id'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'id' => [
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        },
                    ],
                ],
            ]

        );

        // Delete offer by ID
        register_rest_route(
            'ta-forms/v1',
            '/offers/(?P<id>\d+)',
            [
                'methods'             => 'DELETE',
                'callback'            => [$this, 'delete_offer'],
                'permission_callback' => function () {
                    return current_user_can('manage_options'); // restrict to admins
                },
                'args'                => [
                    'id' => [
                        'validate_callback' => fn($param) => is_numeric($param),
                    ],
                ],
            ]
        );
    }

    public function delete_offer(\WP_REST_Request $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ta_forms_offers';
        $id = intval($request['id']);

        $deleted = $wpdb->delete($table_name, ['id' => $id], ['%d']);

        if ($deleted === false) {
            return new \WP_Error('db_error', 'Failed to delete offer', ['status' => 500]);
        }

        if ($deleted === 0) {
            return new \WP_Error('not_found', 'Offer not found', ['status' => 404]);
        }

        return new \WP_REST_Response(['deleted' => true, 'id' => $id], 200);
    }

    public function get_offer_by_id(\WP_REST_Request $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ta_forms_offers';
        $id = intval($request['id']);
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $id), ARRAY_A);

        if (!$row) {
            return new \WP_Error('not_found', 'Offer not found', ['status' => 404]);
        }

        if (!empty($row['field'])) {
            $unserialized = @unserialize($row['field']);
            if ($unserialized !== false) {
                $row['field'] = $unserialized;
            }
        }
        if (!empty($row['meta'])) {
            $unserialized = @unserialize($row['meta']);
            if ($unserialized !== false) {
                $row['meta'] = $unserialized;
            }
        }

        return new \WP_REST_Response($row, 200);
    }

    public function get_offers(\WP_REST_Request $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ta_forms_offers'; // table name
        $results = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);

        foreach ($results as &$row) {
            if (!empty($row['field'])) {
                $unserialized = @unserialize($row['field']);
                if ($unserialized !== false) {
                    $row['field'] = $unserialized; // now it's an array
                }
            }
            if (!empty($row['meta'])) {
                $unserialized = @unserialize($row['meta']);
                if ($unserialized !== false) {
                    $row['meta'] = $unserialized; // now it's an array
                }
            }
        }

        return new \WP_REST_Response($results, 200);
    }

    public function add_cors_header()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function react_preamble()
    {
?>
        <script type="module">
            import RefreshRuntime from 'http://localhost:7500/@react-refresh'
            RefreshRuntime.injectIntoGlobalHook(window);
            window.$RefreshReg$ = () => {};
            window.taFormsString = <?php echo json_encode(Helpers::ta_forms_string()); ?>;
        </script>
    <?php
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public static function enqueue_scripts($hook)
    {
        if ($hook === 'ta-forms_page_react-offers') {

            wp_dequeue_style('common');
            wp_deregister_style('common-css');

            add_action('admin_print_scripts', function () {
                echo wp_print_inline_script_tag(
                    'window.taForms = ' . wp_json_encode([
                        'restUrl' => esc_url_raw(rest_url('ta-forms/v1')),
                        'nonce'   => wp_create_nonce('wp_rest'),
                    ]) . ';'
                );
            });

            wp_enqueue_script(
                'ta-forms-admin',
                plugin_dir_url(__FILE__) . 'assets/js/index.js',
                array(),
                time(),
                true
            );

            // wp_enqueue_script_module(
            //     'ta-forms-admin',
            //     'http://localhost:7500/src/main.jsx',
            //     array('vite-client'),
            //     time(),
            // );
        }
    }

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
        // Add Offers submenu (default view of main menu)
        add_submenu_page(
            'ta-forms',
            esc_html__('Offers', 'ta-forms'),
            esc_html__('Offers', 'ta-forms'),
            'manage_options',
            'ta-forms',
            [OffersView::class, 'ta_forms_offers_view']
        );
        add_submenu_page(
            'ta-forms',
            esc_html__('React Offers', 'ta-forms'),
            esc_html__('React Offers', 'ta-forms'),
            'manage_options',
            'react-offers',
            [$this, 'react_offers_admin']
        );
    }

    /**
     * Options page callback
     */
    public function ta_forms_settings() {}
    public function react_offers_admin()
    {
    ?>
        <div id="root"></div>
<?php
    }
}
