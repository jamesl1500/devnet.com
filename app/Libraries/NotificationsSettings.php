<?php
/**
 * Notifications Settings Library
 * ---
 * Handles user notification settings
 * 
 * @package App\Libraries
 */
namespace App\Libraries;

class NotificationsSettings
{
    /**
     * Notification Settings Type
     * ----
     * Defines the structure of notification settings
     */
    public static $settings_type = [
        'email_notifications' => [
            'type' => 'boolean',
            'default' => true,
            'label' => 'Email Notifications',
            'description' => 'Enable email notifications',
            'key' => 'email_notifications'
        ],
        'sms_notifications' => [
            'type' => 'boolean',
            'default' => false,
            'label' => 'SMS Notifications',
            'description' => 'Enable SMS notifications',
            'key' => 'sms_notifications'
        ],
        'push_notifications' => [
            'type' => 'boolean',
            'default' => false,
            'label' => 'Push Notifications',
            'description' => 'Enable push notifications',
            'key' => 'push_notifications'
        ],
    ];

}