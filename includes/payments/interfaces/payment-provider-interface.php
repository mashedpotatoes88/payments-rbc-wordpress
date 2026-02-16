<?php
// Usage:
// Within this plugin 'giving'
// this interface is to be used by all classes defined under
// payments/providers/{provider name}/client.php
//          eg. payments/providers/dpo-pay/client.php

interface PaymentProviderInterface {
        // core functions
    public function authenticate();
    public function initiateTransaction(array $payload);
    public function processCallback(array $payload);
    public function verifyTransaction(string $transactionToken);
        // helper functions
    public function getProviderName();
}

?>