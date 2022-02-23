<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Models\Traits\HavingAttributes;

/**
 * Representing a user.
 */
class User implements UserContract
{
    use HavingAttributes;

    /**
     * User id.
     * 
     * @var int
     */
    protected $id;

    /**
     * User name.
     * 
     * @var string
     */
    protected $name;

    /**
     * User avatar.
     * 
     * @var string
     */
    protected $avatar;

    /**
     * User role.
     * 
     * @var string
     */
    protected $role;

    /**
     * User role.
     * 
     * @var string
     */
    protected $token;

    /**
     * User professional.
     * 
     * @var ProfessionalContract
     */
    protected $professional;

    /**
     * Getting id.
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Getting name.
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? "{$this->getFirstName()} {$this->getLastName()}";
    }

    /**
     * Getting first name.
     * 
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name ?? $this->last_name;
    }

    /**
     * Getting last name.
     * 
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Getting email.
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Getting avatar.
     * 
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Getting role associated with linked professional.
     * 
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Getting related trustup authorization token.
     * 
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Getting linked professional.
     * 
     * @return ProfessionalContract
     */
    public function getProfessional(): ProfessionalContract
    {
        return $this->professional;
    }

    /**
     * Telling if user can access given account.
     * 
     * @return ProfessionalContract
     */
    public function hasAccessToAccount(AccountContract $account): bool
    {
        return $this->getProfessional()->getAuthorizationKey() === $account->getAuthorizationKey();
    }

    /**
     * Transforming to array.
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'avatar' => $this->avatar,
            'role' => $this->role,
            'professional' => $this->professional->toArray()
        ];
    }

    /**
     * Getting user from an attributes array.
     * 
     * @param array $attributes
     * @return UserContract
     */
    public function fromArray(array $attributes): UserContract
    {
        return $this->setAttributes($attributes);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getToken();
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->getToken();
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'token';
    }
}