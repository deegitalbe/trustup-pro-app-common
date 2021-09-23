<?php
namespace Deegitalbe\TrustupProAppCommon;

use Henrotaym\LaravelApiClient\Client;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerClientContract;

class SynchronizerClient extends Client implements SynchronizerClientContract {}