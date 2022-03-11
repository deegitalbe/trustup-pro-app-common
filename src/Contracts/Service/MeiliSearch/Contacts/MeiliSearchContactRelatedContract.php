<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts;

use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;
use Illuminate\Support\Collection;

/**
 * Contract to complete to be related to contact.
 */
interface MeiliSearchContactRelatedContract
{
    /**
     * Getting related contact.
     * 
     * @return ContactContract
     */
    public function getContact(): ?ContactContract;

    /**
     * Setting related contact from contact collection.
     * 
     * @param Collection $contacts
     * @return static
     */
    public function setContactFromCollection(Collection $contacts): MeiliSearchContactRelatedContract;

    /**
     * Telling if contact is loaded on model.
     * 
     * @return bool
     */
    public function isContactLoaded(): bool;

    /**
     * Getting contact key database column name.
     * 
     * @return string
     */
    public function getContactKey(): string;
}