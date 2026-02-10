<?php

if (!defined('ABSPATH')) {
    exit;
}
// route
add_action('rest_api_init', function() {
    register_rest_route('giving/v1', '/callback', [
        'methods'   => 'POST',
        'callback'  => 'handle_callback',
        'permission_callback' => '__return_true'
    ]);
});
?>