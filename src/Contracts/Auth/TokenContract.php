<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Auth;

/**
 * Trustup token.
 */
interface TokenContract
{
    /**
     * Getting token value.
     * 
     * @return string
     */
    public function get(): ?string;

    /**
     * Getting token in bearer form.
     * 
     * @return string
     */
    public function bearer(): ?string;

    /**
     * Getting token name.
     * 
     * @return string
     */
    public function name(): string;
}