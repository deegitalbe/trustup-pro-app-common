# Installation

## Via composer

    composer require deegitalbe/trustup-pro-app-common

# Configuration

## Environment

Package expects you to have those lines in your .env

    TRUSTUP_ADMIN_URL=
    TRUSTUP_APP_KEY=
 

 - `TRUSTUP_APP_KEY` key should be unique identifier for current app. (e.g: "tasks" for application taches.trustup.pro)
 - `TRUSTUP_ADMIN_URL` should be defined in development mode only. (since package already has correct value for production)

## Publish configuration

You have to publish configuration

    php artisan vendor:publish --provider="Deegitalbe\TrustupProAppCommon\Providers\AppAccountServiceProvider" --tag="config"

You will then have access to `config/trustup_pro_app_common.php` that you have to configure properly.

# Preparing your model
Your model should be in charge of application professionals accounts. Typically it is `App\Models\System\Account.php`

## Default configuration

### Implements interface
Your model should implements this interface

    Deegitalbe\TrustupProAppCommon\Contracts\AccountContract

### Use default trait

You can use this trait in your model to synchronize automatically

    Deegitalbe\TrustupProAppCommon\Models\Synchronizable

## Custom configuration

### Implements interface

Same step as default configuration step

### Define interface methods yourself

    /**
     * Account database id.
     * 
     * @return int
     */
    public function getId(): int;

    /**
     * Account uuid that should be used to retrieve account details.
     * 
     * @return string
     */
    public function getUuid(): string;
    
    /**
     * Application key linked to account.
     * 
     * @return string
     */
    public function getAppKey(): string;

    /**
     * Professional authorization_key linked to account.
     * 
     * @return string
     */
    public function getAuthorizationKey(): string;

    /**
     * Subscription id linked to account.
     * 
     * @return string|null
     */
    public function getSubscriptionId(): ?string;

    /**
     * Subscription status linked to account.
     * 
     * @return string|null
     */
    public function getSubscriptionStatus(): ?string;

    /**
     * Account creation date.
     * 
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;

### Watch model events using package trait

You can use this trait in your model to watch its event and react to it when needed

    Deegitalbe\TrustupProAppCommon\Models\SynchronizeWhenSaved