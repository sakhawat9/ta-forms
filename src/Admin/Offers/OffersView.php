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
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
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
                echo '<td>' . esc_html($email);

                if ('verified' === $row->verify_status) {
                    echo ' <span class="ta_forms_email_verification_done">' . esc_html__('Verified', 'ta-forms') . '</span>';
                } elseif ('pending' === $row->verify_status) {
                    echo ' <span class="ta_forms_email_verification_pending">' . esc_html__('Pending Verification', 'ta-forms') . '</span>';
                    // Resend link: points to admin action or AJAX handler for resending
                    $resend_url = add_query_arg([
                        'page' => 'ta-forms',
                        'action' => 'resend_verification',
                        'offer_id' => $row->id,
                    ], admin_url('admin.php'));
                    echo ' - <a href="' . esc_url($resend_url) . '" title="' . esc_attr__('Send the verification link to the bidder again.', 'ta-forms') . '" class="ta_forms_email_verification_resend">' . esc_html__('Resend', 'ta-forms') . '</a>';
                }

                '</td>';
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
