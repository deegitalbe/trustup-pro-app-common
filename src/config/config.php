<?php

return [
    /**
     * admin.trustup.pro url.
     */
    "admin_url" => env('TRUSTUP_ADMIN_URL', "https://admin.trustup.pro"),
    
    /**
     * trustup.pro url.
     */
    "trustup_pro_url" => env('AUTH_URL', "https://trustup.pro.test"),
    
    /**
     * App key where this package is installed.
     */
    "app_key" => env("TRUSTUP_APP_KEY"),
    
    /**
     * Account model used by this package.
     */
    "account_model" => env("TRUSTUP_ACCOUNT_MODEL", \App\Models\System\Account::class),
    
    /**
     * Header containing cross server authorization secret key.
     */
    "server_authorization_key" => env("TRUSTUP_SERVER_AUTHORIZATION"),
    
    /**
     * Header containing trustup authorization token.
     */
    "trustup_token_header" => env('TRUSTUP_AUTHORIZATION_TOKEN', 'X-TRUSTUP-AUTHORIZATION'),
    
    /**
     * Header containing request account uuid.
     */
    "requested_account_header" => env('REQUESTED_ACCOUNT_HEADER', 'X-ACCOUNT-UUID')
];