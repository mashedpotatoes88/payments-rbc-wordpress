<?php
require_once './payments/providers/mpesa/client.php';
require_once './payments/providers/dpo-pay/client.php';

$mpesa_secrets = [
    "baseUrl" => 'https://sandbox.safaricom.co.ke',
    "consumerKey" => 'sb81XYihydDbvp2tEAVbiyDswnzVyeH6hKGRT1BnwjJXGINs',
    "consumerSecret" => 'pScYjTGkAyWDod9gLdugOJ0KgJG7JBuAgL8sHk5yBzAGFP5s2R33PL1a1LYvrScx',
    "passkey" => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
    "shortCode" => 174379
];

// COMMUNICATE WITH WEB

// obtain payload
$payload = [
    "provider"          => 'mpesa',
    'amount'            => 2,
    'PartyA'            => 254700108512,
    'phone_number'      => 254700108512,
    'callback_url'      => "https://mydomain.com/mpesa-express-simulate/",
    'reference'         => "Ridgeways Baptist",
    'TransactionDesc'   => $payload['description'] ?? 'Giving'
];

// COMMUNICATE WITH API SERVERS
    // 1. choose provider
if ($payload['provider'] === 'mpesa') {
    $client = new MpesaClient($mpesa_secrets);
} 
elseif ($payload['provider'] === 'dpo-pay') {
    $client = new DpoPayClient();
}

    // 2. initiate transaction with provider
$client->initiateTransaction($payload);
?>