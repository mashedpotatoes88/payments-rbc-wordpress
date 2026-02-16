<?php
/*
 * Plugin Name:       My Basics Plugin
 * Description:       Handle the payment through all payment channels on the page 'Giving'
 * Version:           1
 * Author:            Eric Macharia
 */
if (!defined('ABSPATH')) {
    exit;
}
// Definitions
    // path to this folder
define('GIVING_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('GIVING_PLUGIN_URL', plugin_dir_url(__FILE__));

// Requirements
require_once GIVING_PLUGIN_PATH . 'includes/http/handlers/payment-request-handler.php';
require_once GIVING_PLUGIN_PATH . 'includes/http/routes/payment-request-route.php';
require_once GIVING_PLUGIN_PATH . 'includes/http/handlers/callback-handler.php';
require_once GIVING_PLUGIN_PATH . 'includes/http/routes/callback-route.php';

// WP Shortcode
add_shortcode('giving_payment_form', function () {
    return render_payment_request_form();
});

function render_payment_request_form() {
    ob_start();
    $file = GIVING_PLUGIN_PATH . 'frontend/forms/payment-request-form.php';

    if (!file_exists($file)) {
        return '<p>Giving form template not found.</p>';
    }
    require $file;
    return ob_get_clean();
}

// WP Enqueue Script
add_action('wp_enqueue_scripts', 'giving_enqueue_assets');

function giving_enqueue_assets() {
    // Only load on pages where the shortcode is used
    if (!is_singular()){
        return;
    }

    global $post;
    if (!$post || !has_shortcode($post->post_content, 'giving_payment_form')) {
        return;
        }
    // logic
    wp_enqueue_script(
        'giving-payment',
        plugins_url('assets/js/giving-payment.js', __FILE__),
        [], // deps
        '1.0', 
        true // load in footer
    );

    wp_localize_script('giving-payment', 'GivingConfig', [
        'paymentUrl' => rest_url('giving/v1/payment'),
        'callbackUrl' => rest_url('giving/v1/callback')
    ]);
}
?>