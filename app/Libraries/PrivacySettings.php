<?php
/**
 * Privacy Settings Library
 * ---
 * Handles user privacy settings
 * 
 * @package App\Libraries
 */
namespace App\Libraries;

class PrivacySettings
{
    /**
     * Privacy Settings Type
     * ----
     * Defines the structure of privacy settings
     */
    public static array $settings_type = [
        'email_visibility' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Email Visibility',
            'description' => 'Who can see your email address',
            'key' => 'email_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'phone_visibility' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Phone Number Visibility',
            'description' => 'Who can see your phone number',
            'key' => 'phone_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'profile_picture_visibility' => [
            'type' => 'enum',
            'default' => 'everyone',
            'label' => 'Profile Picture Visibility',
            'description' => 'Who can see your profile picture',
            'key' => 'profile_picture_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'followers_list_visibility' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Followers List Visibility',
            'description' => 'Who can see your followers list',
            'key' => 'followers_list_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'followings_list_visibility' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Followings List Visibility',
            'description' => 'Who can see your followings list',
            'key' => 'followings_list_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'profile_visibility' => [
            'type' => 'enum',
            'default' => 'everyone',
            'label' => 'Profile Visibility',
            'description' => 'Who can view your profile',
            'key' => 'profile_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'post_visibility' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Post Visibility',
            'description' => 'Who can view your posts',
            'key' => 'post_visibility',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'only_me' => 'Only Me',
            ],
        ],
        'message_privacy' => [
            'type' => 'enum',
            'default' => 'friends',
            'label' => 'Who Can Message You',
            'description' => 'Control who can send you direct messages',
            'key' => 'message_privacy',
            'options' => [
                'everyone' => 'Everyone',
                'friends' => 'Friends',
                'no_one' => 'No One',
            ],
        ],
        'search_visibility' => [
            'type' => 'boolean',
            'default' => true,
            'label' => 'Appear in Search',
            'description' => 'Allow your profile to appear in search results',
            'key' => 'search_visibility',
        ],
        'show_online_status' => [
            'type' => 'boolean',
            'default' => true,
            'label' => 'Show Online Status',
            'description' => 'Allow others to see when you are online',
            'key' => 'show_online_status',
        ],
        'tag_approval' => [
            'type' => 'boolean',
            'default' => false,
            'label' => 'Tag Approval',
            'description' => 'Require approval before tags appear on your profile',
            'key' => 'tag_approval',
        ],
    ];

}