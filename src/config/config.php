<?php

return [
    /**
     * App key where this package is installed.
     */
    "app_key" => env("TRUSTUP_APP_KEY"),

    /**
     * Urls used by this package.
     */
    "urls" => [
        /**
         * admin.trustup.pro url.
         */
        "admin" => env('TRUSTUP_ADMIN_URL', "https://admin.trustup.pro"),
        
        /**
         * trustup.pro url.
         */
        "trustup_pro" => env('AUTH_URL', "https://trustup.pro"),
    ],

    /**
     * Models used by this package.
     */
    "models" => [
        /**
         * Account model.
         * 
         * It has to implement AccountContract.
         * 
         * @var \Deegitalbe\TrustupProAppCommon\Contracts\AccountContract
         */
        "account" => \App\Models\System\Account::class,

        /**
         * Hostname model.
         */
        "hostname" => \App\Models\System\Hostname::class,
    ],

    /**
     * Api resources used by this package.
     */
    "resources" => [
        /**
         * Account resource.
         * 
         * It is used by account related routes defined lower.
         */
        "account" => \Deegitalbe\TrustupProAppCommon\Http\Resources\Account::class,
    ],
    
    /**
     * Services used by this package.
     */
    "services" => [
        /**
         * Service responsible to store account.
         * 
         * It has to implement StoringAccountServiceContract.
         * It will be automatically called by route storing account (defined lower).
         * 
         * @var \Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract
         */
        "storing_account" => \Deegitalbe\TrustupProAppCommon\Models\Service\StoringAccountService::class,

        /**
         * Configuration related to meilisearch service.
         */
        "meilisearch" => [
            /**
             * Meilisearch models.
             * 
             * These should implement interface MeiliSearchModelContract.
             * 
             * @see \Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Models\MeiliSearchModelContract
             * @var string[]
             */
            "models" => []
        ]
    ],

    /**
     * Request headers used by this package.
     */
    "headers" => [
        /**
         * Header containing trustup authorization token.
         */
        "trustup_token" => "X-TRUSTUP-AUTHORIZATION",
        
        /**
         * Header containing requested account uuid.
         */
        "requested_account" => "X-ACCOUNT-UUID",
    ],

    /**
     * Available routes
     */
    "routes" => [
        /**
         * Account related routes.
         */
        "accounts" => [
            /**
             * Controller method called to store account.
             */
            'store' => [ \Deegitalbe\TrustupProAppCommon\Http\Controllers\Api\AccountController::class, 'store' ],

            /**
             * Controller method called to show account details.
             */
            'show' => [ \Deegitalbe\TrustupProAppCommon\Http\Controllers\Api\AccountController::class, 'show' ],
            
            /**
             * Controller method called to get accouts related to a specific authorization key.
             */
            'by_authorization_key' => [ \Deegitalbe\TrustupProAppCommon\Http\Controllers\Api\AccountController::class, 'byAuthorizationKey' ],
        ]
    ],

    /**
     * Spatie event sourcing related informations.
     * 
     * @see https://spatie.be/docs/laravel-event-sourcing for more details.
     */
    "spatie_event_sourcing" => [
        /**
         * Class to extend to get projector.
         */
        "projector" => \App\Projectors\Projector::class,

        /**
         *  Class to extend to get projector compatible event.
         */
        "event" => \App\Events\StoredEvent::class,

        /**
         * Facade allowing to register projectors.
         */
        "facade" => \Spatie\EventSourcing\Facades\Projectionist::class
    ]
];