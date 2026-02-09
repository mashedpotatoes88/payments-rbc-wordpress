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
// define path to this folder
define('GIVING_PLUGIN_PATH', plugin_dir_path(__FILE__));

// requirements
require_once GIVING_PLUGIN_PATH . 'includes/http/handlers/payment-request-handler.php';
require_once GIVING_PLUGIN_PATH . 'includes/http/routes/payment-request-route.php';
// WP Shortcode
add_shortcode('giving_payment_form', function () {
    return render_payment_request_form();
});

function render_payment_request_form() {
    ob_start();
    $file = GIVING_PLUGIN_PATH . 'frontend/requests/payment-request-form.php';

    if (!file_exists($file)) {
        return '<p>Giving form template not found.</p>';
    }
    require $file;
    return ob_get_clean();
}
?>