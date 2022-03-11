<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts;

use Deegitalbe\TrustupProAppCommon\Models\Contact;
use Illuminate\Support\Collection;

/**
 * Service handling meilisearch contacts.
 */
interface ContactServiceContract
{
    /**
     * Getting contact from uuid.
     * 
     * @param string $uuid Contact uuid.
     * @return Contact|null
     */
    public function getContact(string $uuid): ?Contact;

    /**
     * Getting contacts based on given uuids.
     * 
     * @param string[] $uuids Contacts uuids to retrieve from
     * @return Collection Retrieved contacts.
     */
    public function getContacts(array $uuids): Collection;
}