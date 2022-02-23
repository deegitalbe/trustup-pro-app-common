<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenParserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenProviderContract;

class TokenProvider implements TokenProviderContract
{
    /**
     * Trustup token.
     * 
     * @var TokenContract
     */
    protected $token;

    /**
     * Token parser.
     * 
     * @var TokenParserContract
     */
    protected $token_parser;

    /**
     * Trustup pro api.
     * 
     * @var TrustupProApiContract
     */
    protected $api;

    /**
     * Injecting dependencies.
     * 
     * @param TrustupProApiContract $api Trustup pro api
     * @param TokenParserContract $token_parser Token parser and validator
     * @param TokenContract $token Trustup token
     * @return void
     */
    public function __construct(TokenContract $token, TokenParserContract $token_parser, TrustupProApiContract $api)
    {
        $this->token = $token;
        $this->token_parser = $token_parser;
        $this->api = $api;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return UserContract|null
     */
    public function retrieveById($identifier)
    {
        $user = $this->getUser();

        return optional($user)->getAuthIdentifier() === $identifier
            ? $user
            : null
        ;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return UserContract|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = $this->getUser();
        
        if (!$user):
            return null;
        endif;

        $is_same = $user->getAuthIdentifier() === $identifier && $user->getToken() === $token;

        return $is_same
            ? $user
            : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  UserContract  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return UserContract|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (!$token = ($credentials[$this->token->name()] ?? null)):
            return null;
        endif;

        return $this->retrieveByToken($this->token->name(), $token);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  UserContract  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $credentials_user = $this->retrieveByCredentials($credentials);

        return optional($credentials_user)->getAuthIdentifier() === $user->getAuthIdentifier();
    }

    /**
     * Getting user from incoming request expected header.
     * 
     * @return UserContract
     */
    public function getUser(): ?UserContract
    {
        if ($this->user):
            return $this->user;
        endif;

        if (!$token = $this->token->get()):
            return null;
        endif;

        if (!$jwt = $this->token_parser->parse($token)):
            return null;
        endif;

        return $this->user = $this->token_parser->userFromJwt($jwt) ?? $this->api->getUser();
    }
}