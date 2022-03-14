<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Resources\Traits;

use Deegitalbe\TrustupProAppCommon\Http\Resources\Contact;

trait MeilisearchContactRelatedResource
{
    /**
     * Adding contact to resource if contact is loaded.
     * 
     * @return mixed
     */
    public function addContactWhenLoaded()
    {
        return $this->when($this->isContactLoaded(), function() {
            return $this->addContact();
        });
    }

    /**
     * Adding contact to resource.
     * 
     * @return mixed
     */
    public function addContact()
    {
        return new Contact($this->getContact());
    }
}