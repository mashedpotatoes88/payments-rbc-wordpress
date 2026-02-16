<?php
// Dpo-Pay
    // endpoints
define('DPOPAY_ENDPOINT_CREATE_TOKEN', 'https://secure.3gdirectpay.com/API/v6/');
define('DPOPAY_REDIRECT_PAGE', 'https://secure.3gdirectpay.com/pay.asp?ID=');
    // ridgeways credentials
define('DPOPAY_COMPANY_TOKEN', 'DE0230F2-E7AF-4F65-9570-1DE33500FD60');
define('DPOPAY_SERVICE_TYPE', '102453');
define('DPOPAY_SERVICE_DESCRIPTION', 'Donations');
define('DPOPAY_CURRENCIES_LIST', [
    'USD',
    'KES'
]);
define('DPOPAY_REDIRECT_URL', 'https://www.ridgewaysbaptistchurch.org/giving/');
define('DPOPAY_BACK_URL', 'https://www.ridgewaysbaptistchurch.org/giving/');
?>