<?php

namespace ThemeAtelier\TaForms\Admin\Offers;

if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.

/**
 * Custom post class to register the carousel.
 */
class OffersView
{
    public static function ta_forms_offers_view()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ta_forms_offers';

        // Fetch offers
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY ta_forms_date DESC");

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Offers', 'ta-forms') . '</h1>';

        if (!empty($results)) {
            echo '<table class="widefat fixed striped">';
            echo '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Offer</th>
                        <th>Date</th>
                    </tr>
                  </thead><tbody>';

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . esc_html($row->ta_forms_id) . '</td>';
                echo '<td>' . esc_html($row->ta_forms_name) . '</td>';
                echo '<td>' . esc_html($row->ta_forms_email) . '</td>';
                echo '<td>' . esc_html($row->ta_forms_phone) . '</td>';
                echo '<td>' . esc_html($row->ta_forms_offer) . '</td>';
                echo '<td>' . esc_html($row->ta_forms_date) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>' . esc_html__('No offers found.', 'ta-forms') . '</p>';
        }

        echo '</div>';
    }
}
