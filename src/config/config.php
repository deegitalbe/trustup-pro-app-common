<?php

return [
    "admin_url" => env('TRUSTUP_ADMIN_URL', "https://admin.trustup.pro"),
    "app_key" => env("TRUSTUP_APP_KEY"),
    "account_model" => env("TRUSTUP_ACCOUNT_MODEL", \App\Models\System\Account::class),
    "server_authorization_key" => "clepGJm9E0sCS2Bh5Qo55IXcjmbapikm"
];