<?php
namespace Deegitalbe\TrustupProAppCommon\Api\Client;

use Henrotaym\LaravelApiClient\Client;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;

class TrustupProClient extends Client implements TrustupProClientContract {}