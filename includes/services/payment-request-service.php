<?php
if (!defined('ABSPATH')) {
    exit;
}

class PaymentService {
    public function initiatePayment(array $payload) {
            // 1. choose provider
        $provider_client = $this->resolveProvider($payload['provider']);
            // 2. initiate transaction with provider
        return $provider_client->initiateTransaction($payload);
    }

    protected function resolveProvider(string $provider) {
        return match ($provider) {
            'mpesa'   => new MpesaClient(),
            'dpo-pay' => new DpoPayClient(),
            default   => throw new Exception('Unsupported Provider')
        };
    }
}
?>