<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Auth;

use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Lcobucci\JWT\Token\Plain as JwtToken;

/**
 * Responsible to parse tokens and retrieve users from it.
 */
interface TokenParserContract
{
    /**
     * Parsing trustup authorization token.
     * 
     *  @param string Token to validate and parse.
     *  @return JwtToken|null Null if it could not be parsed or invalid.
     */
    public function parse(string $token): ?JwtToken;

    /**
     * Retrieving a user from given token.
     * 
     * @param JwtToken $token
     * @return UserContract|null
     */
    public function userFromJwt(JwtToken $token): ?UserContract;

    /**
     * Getting user from given token.
     * 
     * @param string $token Token to check.
     * @return UserContract|null Null if any error.
     */
    public function user(string $token): ?UserContract;
}