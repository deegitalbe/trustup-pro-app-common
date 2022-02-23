<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenContract;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;

/**
 * Trustup token.
 */
class Token implements TokenContract
{
    /**
     * Incoming request.
     * 
     * @var Request
     */
    protected $request;

    /**
     * Injectin dependencies.
     * 
     * @param Configuration $config
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Getting token value.
     * 
     * @return string
     */
    public function get(): ?string
    {
        $bearer = $this->bearer();

        return $bearer ? str_replace('Bearer ', '', $bearer) : null;
    }

    /**
     * Getting token in bearer form.
     * 
     * @return string
     */
    public function bearer(): ?string
    {
        return $this->request->header($this->name());
    }

    /**
     * Getting token name.
     * 
     * @return string
     */
    public function name(): string
    {
        return Package::trustupAuthorizationHeader();
    }
}