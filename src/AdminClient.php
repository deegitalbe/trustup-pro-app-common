<?php
namespace Deegitalbe\TrustupProAppCommon;

use Henrotaym\LaravelApiClient\Client;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AdminClientContract;

class AdminClient extends Client implements AdminClientContract {}