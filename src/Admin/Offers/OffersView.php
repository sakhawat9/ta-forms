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
        $table_name = $wpdb->prefix . 'ta_forms_offers_1';

        
        // Fetch offers
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        // print_r($results);
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Offers', 'ta-forms') . '</h1>';
        
        if (!empty($results)) {
            echo '<table class="fixed widefat striped">';
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
                $field = maybe_unserialize($row->field);
                $name = $field['ta_forms_full_name'] ?? '';
                $email = $field['ta_forms_email'] ?? '';
                $phone = $field['ta_forms_phone'] ?? '';
                $offer = $field['ta_forms_offer'] ?? '';
                echo '<tr>';
                echo '<td>' . esc_html($row->id) . '</td>';
                echo '<td>' . esc_html($name) . '</td>';
                echo '<td>' . esc_html($email) . '</td>';
                echo '<td>' . esc_html($phone) . '</td>';
                echo '<td>' . esc_html($offer) . '</td>';
                echo '<td>' . esc_html($row->created_at) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>' . esc_html__('No offers found.', 'ta-forms') . '</p>';
        }

        echo '</div>';
    }
}
