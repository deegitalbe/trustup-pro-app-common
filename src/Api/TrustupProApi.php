<?php
namespace Deegitalbe\TrustupProAppCommon\Api;

use Deegitalbe\TrustupProAppCommon\Auth\Token;
use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetUserFailed;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetExpectedAccountFailed;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\UserNotHavingAccessToAccount;

/**
 * Representing actions that are available with trustup.pro
 */
class TrustupProApi implements TrustupProApiContract
{
    /**
     * Underlying client.
     * 
     * @var TrustupProClientContract
     */
    protected $client;

    /**
     * Token related actions
     * 
     * @var Token
     */
    protected $token;

    /**
     * Constructing class.
     * 
     * @param TrustupProClientContract $client
     * @return void
     */
    public function __construct(TrustupProClientContract $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * Getting authenticated user linked to current request.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl('api/user');
        
        $response = $this->client->try($request, new GetUserFailed);

        if ($response->failed()):
            report($response->error());
            return null;
        endif;

        $user = $response->response()->get(true)['user'];

        return $this->toUserModel($user);
    }

    /**
     * Transforming raw user attributes to user model.
     * 
     * @param array $attributes
     * @return UserContract
     */
    protected function toUserModel(array $attributes): UserContract
    {
        // Setting up role.
        $attributes['role'] = $attributes['default_professional']['user_role'];
        
        // Setting up professional.
        $attributes['professional'] = app()->make(ProfessionalContract::class)->fromArray($attributes['default_professional']);
        $attributes['token'] = $this->token->get();
        unset($attributes['default_professional']);

        return app()->make(UserContract::class)->fromArray($attributes);
    }
}