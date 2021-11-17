<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service;

use Illuminate\Http\Request;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Service storing account.
 */
interface StoringAccountServiceContract
{
    /**
     * Storing account based on given request.
     * 
     * @param Request $request
     * @return AccountContract
     */
    public function store(Request $request): AccountContract;
}