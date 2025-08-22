<?php

/**
 * Views class for Notifications options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package ta-forms
 * @subpackage ta-forms/Admin/Views/Notifications
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\TaForms\Admin\Views;

use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

class Notifications
{
    /**
     * Create Option fields for the setting options.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function options($prefix)
    {
        //
        // Notifications section.
        TaForms::createSection(
            $prefix,
            array(
                'title'  => esc_html__('Notifications', 'ta-forms'),
                'icon'   => 'fa fa-bell',
                'fields' => array(
                    array(
                        'id'         => 'submission_email_subject',
                        'type'       => 'text',
                        'title'      => esc_html__('Email Notification Subject', 'ta-forms'),
                        'subtitle'   => esc_html__('Type subject for the email notification.', 'ta-forms'),
                        'default'    => 'A New Proposal is Submitted',
                    ),
                    array(
                        'id'         => 'submission_email_body',
                        'type'       => 'wp_editor',
                        'title'      => esc_html__('Email Notification Body', 'ta-forms'),
                        'default'    => 'Dear Administrator,
                        A new proposal has been submitted.

                        Name: {name}
                        Email: {email}
                        Subject: {subject}
                        Offer: {offer}
                        Proposal: {proposal}\n\n{date}',
                        'desc'    => '<div class="email_notification_variables"></div>',
                    ),
                    array(
                        'id'         => 'submission_email_notification_to',
                        'type'       => 'textarea',
                        'title'      => esc_html__('Email(s) to Notify', 'ta-forms'),
                        'desc'       => esc_html__('Enter the email address(es) that will receive a notification for each pending proposal. For multiple emails, use comma between these.', 'ta-forms'),
                        'default'    => get_option('admin_email'),
                    ),
                ),
            )
        );
    }
}
