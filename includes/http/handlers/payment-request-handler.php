<?php
require_once './payments/providers/mpesa/client.php';
require_once './payments/providers/dpo-pay/client.php';

function giving_handle_payment_request(WP_REST_REQUEST $request) {
    // 1. Extract
    $data = $request->get_json_params();

    // 2. Validate
    if (empty($data['amount']) || empty($data['$provider'])) {
        return new WP_Error(
            'invalid_request',
            'Missing required fields',
            ['status' => 400]
        );
    }

    // 3. Normalise
    $payload = [
        'provider'     => $data['provider'],
        'amount'       => (int) $data['amount'],
        'phone_number' => $data['phone_number'] ?? null,
        'reference'    => $data['reference'] ?? 'Giving',
        'description'  => $data['description'] ?? 'Giving'
    ];

    // 4. Call service
    $payment_request_service = new PaymentService();
    $result = $payment_request_service->initiatePayment($payload);

    // 5. return
    return [
        'status' => 'initiated',
        'data'   => $result
    ];
}