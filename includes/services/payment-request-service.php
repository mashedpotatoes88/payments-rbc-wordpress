<?php
require_once GIVING_PLUGIN_PATH . 'includes/payments/providers/dpo-pay/client.php';
require_once GIVING_PLUGIN_PATH . 'includes/payments/providers/mpesa/client.php';
if (!defined('ABSPATH')) {
    exit;
}

class PaymentService {
    public function initiateTransaction(array $payload) {
            // 1. choose provider
        $provider_client = $this->resolveProvider($payload['provider'], $payload);
            // 2. initiate transaction with provider
        $result = $provider_client->initiateTransaction($payload);
        return new WP_REST_Response($result, 200);
    }

    protected function resolveProvider(string $provider, array $payload) {
        return match ($provider) {
            'mpesa'   => new MpesaClient(),
            'dpo-pay' => new DpoPayClient($payload),
            default   => throw new Exception('Unsupported Provider')
        };
    }
}
?>