<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenProviderContract;

class TokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * The currently authenticated user.
     *
     * @var UserContract
     */
    protected $user;

    /**
     * The user provider implementation.
     *
     * @var TokenProviderContract
     */
    protected $provider;

    /**
     * Trustup token.
     * 
     * @var TokenContract
     */
    protected $token;

    /**
     * Injecting dependencies.
     * 
     * @param TokenContract $token Trustup token
     * @param  TokenProviderContract $provider Token user provider
     * @return void
     */
    public function __construct(TokenContract $token, TokenProviderContract $provider, TrustupProApiContract $api)
    {
        $this->token = $token;
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return UserContract|null
     */
    public function user()
    {
        if ($this->user):
            return $this->user;
        endif;

        return $this->user = $this->provider->getUser();
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return !!$this->provider->retrieveByCredentials($credentials);
    }

    /**
     * Trying to authenticate user based on given professional authorization key.
     *
     * @param string $authorizationKey Professional authorization key.
     * @return UserContract|null
     */
    public function authentificateByProfessionalAuthorizationKey(string $authorizationKey): ?UserContract
    {
        return $this->user = $this->provider->retrieveByProfessionalAuthorizationKey($authorizationKey);
    }

}