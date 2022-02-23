<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain as JwtToken;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenParserContract;

/**
 * Responsible to parse tokens and retrieve users from it.
 */
class TokenParser implements TokenParserContract
{
    /** 
     * Token verification config.
     * 
     * @var Configuration
     */
    protected $config;

    /**
     * Injectin dependencies.
     * 
     * @param Configuration $config
     * @return void
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * Parsing trustup authorization token.
     * 
     *  @param string Token to validate and parse.
     *  @return JwtToken|null Null if it could not be parsed or invalid.
     */
    public function parse(string $token): ?JwtToken
    {
        [, $parsed] = Helpers::try(function() use ($token) {
            return $this->config->parser()->parse($token);
        });

        if (!$parsed):
            return null;
        endif;

        if(!$this->config->validator()->validate($parsed, ...$this->config->validationConstraints())):
            return false;
        endif;

        return $parsed;
    }

    /**
     * Retrieving a user from given token claims.
     * 
     * @param JwtToken $token
     * @return UserContract|null
     */
    public function userFromJwt(JwtToken $token): ?UserContract
    {
        if (!$this->checkClaims($token)):
            return null;
        endif;

        /** @var UserContract */
        $user = app()->make(UserContract::class);
        
        /** @var ProfessionalContract */
        $professional = app()->make(ProfessionalContract::class);

        $attributes = $token->claims()->get('user');
        $attributes['professional'] = $professional->fromArray($token->claims()->get('professional'));
        $attributes['token'] = (string) $token;

        return $user->fromArray($attributes);
    }

    /**
     * Getting user from given token.
     * 
     * @param string $token Token to check.
     * @return UserContract|null Null if any error.
     */
    public function user(string $token): ?UserContract
    {
        $jwt_token = $this->parse($token);

        return $jwt_token
            ? $this->userFromJwt($jwt_token)
            : null;
    }

    /**
     * Checking token claims presence.
     * 
     * @param JwtToken $token Token where to check claims presence.
     * @return bool
     */
    protected function checkClaims(JwtToken $token): bool
    {
        return $token->claims()->get('user')
            && $token->claims()->get('professional');
    }
}