<?php
require_once GIVING_PLUGIN_PATH . 'includes/payments/interfaces/payment-provider-interface.php';
    

class DpoPayClient implements PaymentProviderInterface {
    // predefined
        // http
    protected string $endpoint_createToken;
    protected string $endpoint_chargeToken;
    protected string $redirectURL;
    protected string $backURL;
        // merchant details: Ridgeways Baptist Church
    protected string $companyToken;
    protected string $serviceType;
    protected string $serviceDescription;

    // passed
    protected int $paymentAmount;
    protected string $paymentCurrency;
    protected string $companyRef;

    public function __construct(array $config){
        // predefined
			// http
        $this->redirectURL = DPOPAY_REDIRECT_URL;
        $this->backURL = DPOPAY_BACK_URL;
        $this->endpoint_createToken = DPOPAY_ENDPOINT_CREATE_TOKEN;
        $this->endpoint_chargeToken = DPOPAY_REDIRECT_PAGE;
			// credentials
        $this->companyToken = DPOPAY_COMPANY_TOKEN;
        $this->paymentCurrency = DPOPAY_CURRENCIES_LIST[1];
        $this->serviceType = DPOPAY_SERVICE_TYPE;
        $this->serviceDescription = DPOPAY_SERVICE_DESCRIPTION;
        // passed in
        $this->paymentAmount = $config['amount'];
        $this->companyRef = $config['companyRef'] ?? 'unspecified';
    }

    // AUTHENTICATE
    public function authenticate() {
        $request = 'createToken';
        $timestamp = date('Y/m/d H:i:s');
        $xmlData = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <API3G>
            <CompanyToken>{$this->companyToken}</CompanyToken>
            <Request>{$request}</Request>
            <Transaction>
                <PaymentAmount>{$this->paymentAmount}</PaymentAmount>
                <PaymentCurrency>{$this->paymentCurrency}</PaymentCurrency>
                <CompanyRef>49FKEOA</CompanyRef>
                <RedirectURL>{$this->redirectURL}</RedirectURL>
                <BackURL>{$this->backURL}</BackURL>
                <CompanyRefUnique>0</CompanyRefUnique>
                <PTL>5</PTL>
            </Transaction>
            <Services>
                <Service>
                    <ServiceType>{$this->serviceType}</ServiceType>
                    <ServiceDescription>{$this->serviceDescription}</ServiceDescription>
                    <ServiceDate>{$timestamp}</ServiceDate>
                </Service>
            </Services>
        </API3G>";

        // PREP REQUEST
            // init curl
        $ch = curl_init();
        if (!$ch) {
            die("Couldn't initialize a cURL handle");
        }
            // set curl options
        curl_setopt($ch, CURLOPT_URL, $this->endpoint_createToken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);

        // SEND REQUEST
        $response = curl_exec($ch);
        $data = simplexml_load_string($response);
        return $data;
    }
    // INITIATE TRANSACTION
    public function initiateTransaction(array $payload) {
        // call required functions
            // get transaction Token
        $transactionToken = (string) $this->authenticate()->TransToken;         
        // get payment url for redirect
        $dpo_redirect_url = $this->endpoint_chargeToken . $transactionToken;
        wp_redirect($dpo_redirect_url);
    }
    public function processCallback(array $payload) {
        // code here
    } 
    public function verifyTransaction($transactionToken) {
        $xmlData = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <API3G>
            <CompanyToken>{$this->companyToken}</CompanyToken>
            <Request>verifyToken</Request>
            <TransactionToken>{$transactionToken}</TransactionToken>
        </API3G>";

        // PREPARE FOR REQUEST
            // init cURL
        $ch = curl_init();
            // set curl options
        curl_setopt($ch, CURLOPT_URL, $this->endpoint_createToken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);

        // SEND REQUEST
        $response = curl_exec($ch);
            // return
        return simplexml_load_string($response);

    } 
    public function getProviderName() {
        // code here

    } 
 }
?>