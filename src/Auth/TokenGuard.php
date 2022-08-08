<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

use App\Events\Users\UserCreated;
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
    public function __construct(TokenContract $token, TokenProviderContract $provider)
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

    public function userByProfessionalAuthorizationKey(string $key): ?UserContract
    {
        return $this->provider->getUserByProfessionalAuthorizationKey($key);
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

}