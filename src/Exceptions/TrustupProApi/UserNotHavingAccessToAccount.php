<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi;

use Exception;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

class UserNotHavingAccessToAccount extends Exception {
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Authentified trustup user can't access expected account.";

    /**
     * Expected account.
     * 
     * @var AccountContract
     */
    protected $account;

    /**
     * Authentified user.
     * 
     * @var UserContract
     */
    protected $user;

    /**
     * Getting exception for given user and account.
     * 
     * @return self
     */
    public static function get(UserContract $user, AccountContract $account): self
    {
        $instance = new self;
        $instance->user = $user;
        $instance->account = $account;

        return $instance;
    }


    /**
     * Exception context.
     * 
     * @return array
     */
    public function context()
    {
        return [
            'user' => $this->user->toArray(),
            'account' => [
                'uuid' => $this->account->getUuid(),
                'authorization_key' => $this->account->getAuthorizationKey(),
                'app' => $this->account->getAppKey()
            ]
        ];
    }
}