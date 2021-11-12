<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi;

use Exception;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

class GetExpectedAccountFailed extends Exception {
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "No account found matching given uuid.";

    /**
     * Account uuid that was searched.
     * 
     * @var string|null
     */
    protected $account_uuid;

    /**
     * Constructing exception.
     * 
     * @param string|null $account_uuid
     * @return self
     */
    public static function forUuid(?string $account_uuid): self
    {
        $instance = new self();
        $instance->account_uuid = $account_uuid;
        
        return $instance;
    }

    /**
     * Exception context.
     * 
     * @return array
     */
    public function context()
    {
        return [
            'account_uuid' => $this->account_uuid
        ];
    }
}