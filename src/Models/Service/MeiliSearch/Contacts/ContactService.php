<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service\MeiliSearch\Contacts;

use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts\ContactServiceContract;
use Deegitalbe\TrustupProAppCommon\Models\Contact;
use Illuminate\Support\Collection;
use Laravel\Scout\Engines\MeiliSearchEngine;
use MeiliSearch\Client as MeiliSeachClient;
use MeiliSearch\Endpoints\Indexes;

/**
 * Service helping 
 */
class ContactService implements ContactServiceContract
{
    /**
     * Underlying MeiliSearch client.
     * @var MeiliSeachClient $client
     */
    protected $client;

    /**
     * Authentication related details.
     * @var AuthenticationRelatedContract
     */
    protected $auth;

    /**
     * Instanciating dependencies.
     * 
     * @param MeiliSearchEngine $client
     * @return void
     */
    public function __construct(MeiliSearchEngine $client, AuthenticationRelatedContract $auth)
    {
        $this->client = $client;
        $this->auth = $auth;
    }

    /**
     * Getting index.
     * 
     * @return Indexes
     */
    protected function getIndex(): Indexes
    {
        return $this->index ?? $this->index = $this->client->index($this->getIndexName());
    }

    /**
     * Getting contacts index.
     * 
     * @return string
     */
    protected function getIndexName(): string
    {
        return join("_", [
            $this->auth->getAccount()->getAuthorizationKey(),
            $this->auth->getAccount()->getAppKey(),
            "contacts"
        ]);
    }

    /**
     * Getting contact from uuid.
     * 
     * @param string $uuid Contact uuid.
     * @return Contact|null
     */
    public function getContact(string $uuid): ?Contact
    {
        if (!$raw = $this->getIndex()->getDocument($uuid)):
            return null;
        endif;

        return $this->arrayToContact($raw);
    }

    /**
     * Getting contacts based on given uuids.
     * 
     * @param string[] $uuids Contacts uuids to retrieve from
     * @return Collection Retrieved contacts.
     */
    public function getContacts(array $uuids): Collection
    {
        $filter = collect($uuids)->map(function(string $uuid) {
            return "uuid = $uuid";
        })->join(' OR ');

        $hits = $this->getIndex()->search('', ['filter' => $filter])->getHits();

        return collect($hits)->map(function(array $raw) {
            return $this->arrayToContact($raw);
        });
    }

    /**
     * Getting contact from raw data.
     * 
     * @return Contact
     */
    protected function arrayToContact(array $raw): Contact
    {
        /** @var Contact */
        $contact = app()->make(Contact::class);

        return $contact->setAttributes($raw);
    }
}