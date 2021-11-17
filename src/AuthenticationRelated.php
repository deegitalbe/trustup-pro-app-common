<?php
namespace Deegitalbe\TrustupProAppCommon;

use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
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
     * Account environment.
     * 
     * @return AccountContract|null
     */
    protected $account;

    /**
     * Trustup pro API.
     * 
     * @var TrustupProApiContract
     */
    protected $api;

    /**
     * Telling if authenticated was already retrieved from trustup.pro
     * 
     * @var bool
     */
    protected $api_called = false;

    /**
     * Creating instance.
     * 
     * @param TrustupProApiContract $api
     */
    public function __construct(TrustupProApiContract $api)
    {
        $this->api = $api;
    }

    /**
     * Getting authenticated user.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract
    {
        if (!$this->api_called):
            $this->user = $this->api->getUser();
            $this->api_called = true;
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
     * Authenticated user.
     * 
     * @return UserContract|null
     */
    public function setAccount(AccountContract $account)
    {
        $this->account = $account;
    }
}