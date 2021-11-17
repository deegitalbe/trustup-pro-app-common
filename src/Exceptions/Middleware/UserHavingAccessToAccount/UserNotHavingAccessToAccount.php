<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\Middleware\UserHavingAccessToAccount;

use Exception;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Exception when authenticated user is trying to access unallowed professional.
 */
class UserNotHavingAccessToAccount extends Exception {
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "authenticated user can't access expected account.";

    /**
     * Expected account.
     * 
     * @var AccountContract
     */
    protected $account;

    /**
     * Authenticated user.
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