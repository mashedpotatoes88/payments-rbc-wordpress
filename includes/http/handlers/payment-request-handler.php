<?php
require_once GIVING_PLUGIN_PATH . 'includes/services/payment-request-service.php';

function handle_payment_request(WP_REST_Request $request) {
    // 1. Extract
    $data = $request->get_json_params();

    // 2. Validate
    if (empty($data['amount']) || empty($data['provider'])) {
        return new WP_Error(
            'invalid_request',
            'Missing required fields',
            ['status' => 400]
        );
    }

    // 3. Normalise
    $payload = [
        'provider'     => $data['provider'] ?? null,
        'amount'       => (int) $data['amount'],
        'phone_number' => $data['phone'] ?? null,
        'callbackUrl'  => rest_url('giving/v1/callback'),
        'reference'    => $data['reference'] ?? 'Giving',
        'description'  => $data['description'] ?? 'Giving'
    ];

    // 4. Call service
        // init payment request service
    $payment_request_service = new PaymentService();
        // call service function
    $result = $payment_request_service->initiateTransaction($payload);

    // 5. return
    return [
        'status' => 'Payment request initiated by handler!',
        'service response'   => $result
    ];
}