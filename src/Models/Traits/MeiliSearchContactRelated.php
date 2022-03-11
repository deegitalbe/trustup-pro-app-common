<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts\ContactServiceContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts\MeiliSearchContactRelatedContract;
use Illuminate\Support\Collection;

/**
 * Trait fullfilling meilisearch contact related contract.
 * 
 * @see MeiliSearchContactRelatedContract
 */
trait MeiliSearchContactRelated
{
    /**
     * Related contact.
     * 
     * @var ContactContract|null
     */
    private $contact = null;

    /**
     * Telling if contact is loaded.
     * 
     * @return bool
     */
    private $contactLoaded = false;

    /**
     * Telling if contact is loaded.
     * 
     * @return bool
     */
    public function isContactLoaded(): bool
    {
        return $this->contactLoaded;
    }

    /**
     * Getting contact.
     * 
     * @return ContactContract|null
     */
    public function getContact(): ?ContactContract
    {
        if (!$this->contactLoaded):
            /** @var ContactServiceContract */
            $service = app()->make(ContactServiceContract::class);
            $this->setContact($service->getContact($this->{$this->getContactKey()}));
        endif;

        return $this->contact;
    }

    /**
     * Loading related contact.
     * 
     * @return MeiliSearchContactRelatedContract
     */
    public function loadContact(): MeiliSearchContactRelatedContract
    {
        $this->getContact();

        return $this;
    }

    /**
     * Setting related contact.
     * 
     * @param ContactContract|null
     * @return static
     */
    protected function setContact(?ContactContract $contact): MeiliSearchContactRelatedContract
    {
        $this->contact = $contact;
        $this->contactLoaded = true;
        
        return $this;
    }

    /**
     * Setting related contact from contact collection.
     * 
     * @param Collection $contacts
     * @return static
     */
    public function setContactFromCollection(Collection $contacts): MeiliSearchContactRelatedContract
    {
        $contact = $contacts->first(function(ContactContract $contact) {
            return $contact->getUuid() === $this->{$this->getContactKey()};
        });

        return $this->setContact($contact);
    }

    /**
     * Getting contact key database column name.
     * 
     * @return string
     */
    public function getContactKey(): string
    {
        return "contact_uuid";
    }
}