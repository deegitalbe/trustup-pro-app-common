<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Representing a user.
 */
interface UserContract extends Arrayable, Authenticatable
{
    /**
     * Getting id.
     * 
     * @return int
     */
    public function getId(): int;
    
    /**
     * Getting name.
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Getting first name.
     * 
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Getting last name.
     * 
     * @return string
     */
    public function getLastName(): string;

    /**
     * Getting email.
     * 
     * @return string
     */
    public function getEmail(): string;

    /**
     * Getting avatar.
     * 
     * @return string|null
     */
    public function getAvatar(): ?string;

    /**
     * Getting role associated with linked professional.
     * 
     * @return string|null
     */
    public function getRole(): ?string;

    /**
     * Getting related trustup authorization token.
     * 
     * @return string
     */
    public function getToken(): string;

    /**
     * Getting linked professional.
     * 
     * @return ProfessionalContract
     */
    public function getProfessional(): ProfessionalContract;

    /**
     * Telling if user can access given account.
     * 
     * @return ProfessionalContract
     */
    public function hasAccessToAccount(AccountContract $account): bool;

    /**
     * Getting user from an attributes array.
     * 
     * @param array $attributes
     * @return UserContract
     */
    public function fromArray(array $attributes): UserContract;
}