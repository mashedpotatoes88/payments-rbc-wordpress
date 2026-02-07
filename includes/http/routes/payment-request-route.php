<?php
if (!defined('ABSPATH')) {
    exit;
}

// route
add_action('rest_api_init', function() {
    register_rest_route('giving/v1', '/payment', [
        'methods' => 'POST',
        'callback' => 'giving_handle_payment_request',
        'permission_callback' => '__return_true'
    ]);
})
?>