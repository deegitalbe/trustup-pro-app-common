<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Responsible to update/delete/create synchronized accounts.
 */
interface SynchronizerContract
{
    /**
     * Creating account in database.
     * 
     * @param AccountContract $account Account that should be created.
     */
    public function create(AccountContract $account);

    /**
     * Updating account in database.
     * 
     * @param AccountContract $account Account that should be updated.
     */
    public function update(AccountContract $account);

    /**
     * deleting account from database.
     * 
     * @param AccountContract $account Account that should be deleted.
     */
    public function delete(AccountContract $account);
}