<?php
namespace Deegitalbe\TrustupProAppCommon;

use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;

/**
 * Useful information concerning current authentication environment.
 */
class AuthenticationRelated implements AuthenticationRelatedContract
{
    /**
     * Authenticated user.
     * 
     * @return UserContract|null
     */
    protected $user;

    /**
     * Current app.
     * 
     * @return AppContract|null
     */
    protected $app;

    /**
     * Account environment.
     * 
     * @return AccountContract|null
     */
    protected $account;

    /**
     * Admin app API.
     * 
     * @var AdminAppApiContract
     */
    protected $admin_api;

    /**
     * Telling if authenticated user was already retrieved from trustup.pro
     * 
     * @var bool
     */
    protected $user_retrieved = false;

    /**
     * Telling if current app was already retrieved from admin.trustup.pro
     * 
     * @var bool
     */
    protected $app_retrieved = false;

    /**
     * Creating instance.
     * 
     * @param AdminAppApiContract $admin_api
     */
    public function __construct(AdminAppApiContract $admin_api)
    {
        $this->admin_api = $admin_api;
    }

    /**
     * Getting authenticated user.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract
    {
        if (!$this->user_retrieved):
            $this->user = auth()->user();
            $this->user_retrieved = true;
        endif;
        
        return $this->user;
    }

    /**
     * Account environment.
     * 
     * @return AccountContract|null
     */
    public function getAccount(): ?AccountContract
    {
        return $this->account;
    }

    /**
     * Getting current app for current request.
     * 
     * @return AppContract|null
     */
    public function getCurrentApp(): ?AppContract
    {
        if (!$this->app_retrieved):
            $this->app = $this->admin_api->getCurrentApp();
            $this->app_retrieved = true;
        endif;
        
        return $this->app;
    }

    /**
     * Setting account for current request (should not be called manually).
     * 
     * @param AccountContract|null $account
     * @return static
     */
    public function setAccount(?AccountContract $account): AuthenticationRelatedContract
    {
        $this->account = $account;

        return $this;
    }
}