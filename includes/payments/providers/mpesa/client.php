<?php
require_once './includes/payments/interfaces/payment-provider-interface.php';


class MpesaClient implements PaymentProviderInterface {
    protected string $baseUrl;
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $passkey;
    protected string $shortCode;

    protected string $encodedString;
    protected ?string $accessToken = null;

    // constructor
    // public function __construct(array $config) {
    //     $this->baseUrl = $config['baseUrl'];
    //     $this->consumerKey = $config['consumerKey'];
    //     $this->consumerSecret = $config['consumerSecret'];
    //     $this->passkey = $config['passkey'];
    //     $this->shortCode = $config['shortCode'];
    //     $this->encodedString = base64_encode(
    //         $this->consumerKey . ':' . $this->consumerSecret
    //     );
    // }

    // AUTHENTICATE
    public function authenticate() {
        // get from cache './token.json'
        $accessToken = $this->getCachedToken();
        if ($accessToken) {
            return $accessToken;
        }

        // PREPARE FOR REQUEST 
            // init cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 
        $this->baseUrl . "/oauth/v1/generate?grant_type=client_credentials"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . $this->encodedString
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // SEND REQUEST
        $response = curl_exec($ch); // get response
        $data = json_decode($response, true); // decode response

        // STORE IN FILE AND RETURN
        $accessToken = $data['access_token'];
        $expiresIn = $data['expires_in'];
        $this->cacheToken($accessToken, $expiresIn);
        return $accessToken ?? null;
    }
        // helper function 1
    private function getCachedToken() {
        // check if file exists
        $path = __DIR__ . '/token.json';
        if (!file_exists($path)) {
            return null;
        }
        // check if current time is past expiry time
        $data = json_decode(file_get_contents($path), true);
        if ($data['expires_at'] <= time()) {
            return null;
        }
        // else return the valid access token
        return $data['access_token'];
    }
        // helper function 2
    private function cacheToken($accessToken, $expiresIn) {
        $data = [
            'access_token' => $accessToken,
            'expires_at' => time() + $expiresIn - 60
        ];
        file_put_contents(__DIR__ . '/token.json', json_encode($data));
    }

    // INITIATE TRANSACTION
    public function initiateTransaction(array $payload) {
        // pre-set
        $accessToken = $this->authenticate(); //get access token
        $timestamp = date('YmdHis');
        $password = base64_encode(
            $this->shortCode . $this->passkey . $timestamp
        );

            // request body
        $requestBody = [
            'BusinessShortCode' => $this->shortCode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $payload['amount'],
            'PartyA'            => $payload['phone_number'],
            'PartyB'            => $this->shortCode,
            'PhoneNumber'       => $payload['phone_number'],
            'CallBackURL'       => $payload['callback_url'],
            'AccountReference'  => $payload['reference'],
            'TransactionDesc'   => $payload['description'] ?? 'Giving'
        ];
        
        // PREPARE FOR REQUEST
            // init cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 
        $this->baseUrl . "/mpesa/stkpush/v1/processrequest"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // SEND REQUEST
        $response = curl_exec($ch);

        // RETURN
        return json_decode($response, true);
    }

    public function handleCallback(array $payload): array {
        $stkCallback = $payload['Body']['stkCallback'] ?? null;
        // 1. if callback is somehow empty
        if (!$stkCallback) {
            return [
                'provider' => 'mpesa',
                'status'   => 'invalid',
                'raw'      => $payload
            ];
        }
        // 2. if callback is true
        $resultCode = $stkCallback['ResultCode'] ?? null;
        $status = ((int)$resultCode === 0) ? 'success' : 'failed';
            // init metadata array
        $meta = [];
        if (isset($stkCallback['CallbackMetadata']['Item'])) {
                // 2. loop and extract metadata
            foreach ($stkCallback['CallbackMetadata']['Item'] as $item) {
                $meta[$item['Name']] = $item['Value'] ?? null;    
            }
        } 
        // return
        return [
            'provider'  => 'mpesa',
            'status'    => $status,
            'receipt'   => $meta['MpesaReceiptNumber'] ?? null,
            'amount'    => $meta['Amount'] ?? null,
            'phone'     => $meta['PhoneNumber'] ?? null,
            'date'      => $meta['TransactionDate'] ?? null,
            'reference' => $stkCallback['CheckoutRequestID'] ?? null,
            'raw'       => $payload
        ];         
    } 
    public function verifyTransaction() {
        // code here

    } 
    public function getProviderName() {
        // code here

    } 
 }
?>