<?php

if (!defined('ABSPATH')) {
    exit;
}
define('GIVING_PLUGIN_PATH', plugin_dir_path(__FILE__));

add_shortcode('giving_payment_form', function() {
    ob_start();
    require plugin_dir_path(__FILE__) . 'frontend/requests/payment-request.php';
    return ob_get_clean();
})

?>