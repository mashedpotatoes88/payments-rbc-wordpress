<?php
require_once GIVING_PLUGIN_PATH . 'includes/services/callback-service.php';

function handle_callback(WP_REST_Request $request) {
    // 1. Extract Data from request
    $data = $request->get_json_params();
    // 2. Validate and 3. Normalise
    // skipped for now
    // assumed providers will always return valid json

    // 4. Call Service
        // init service
    $callback_service = new CallbackService();
        // call service function
    $result = $callback_service->processCallback($data);    

    // 5. Return
    return new WP_REST_Response([
        'ResultCode'    => 0,
        'ResultDesc'    => 'Accepted'
    ], 200);
}