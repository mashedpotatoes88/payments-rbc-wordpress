<?php
require_once GIVING_PLUGIN_PATH . 'includes/payments/providers/mpesa/client.php';

if (!defined('ABSPATH')) {
    exit;
}

class CallbackService {
    public function processCallback(array $payload) {
        // check
        if (!isset($payload['provider'])) {
            error_log("Callback Service halted: Provider name missing");
        }
            // 1. choose provider
        $provider_client = $this->resolveProvider($payload['provider']);
            // 2. call function to process callback
        return $provider_client->processCallback($payload);
    }

    protected function resolveProvider(string $provider) {
        return match ($provider) {
            'mpesa'   => new MpesaClient(),
            'dpo-pay' => new DpoPayClient(),
            default   => throw new Exception('Unsupported Provider')
        };
    }
}