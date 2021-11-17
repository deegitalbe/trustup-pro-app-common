<?php
namespace Deegitalbe\TrustupProAppCommon\Rules;

use Illuminate\Contracts\Validation\Rule;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;

/**
 * Rule determining if authenticated user is having same authorization key than this parameter.
 */
class UserHavingAuthorizationKey implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value === $this->getUserAuthorizationKey();
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return "User making request do not have access to professional matching :attribute.";
    }

    /**
     * Getting authenticated user authorization key.
     * 
     * @return string|null
     */
    protected function getUserAuthorizationKey(): ?string
    {
        return Helpers::optional(app()->make(AuthenticationRelatedContract::class), 'getUser()', 'getProfessional()')->getAuthorizationKey();
    }
}