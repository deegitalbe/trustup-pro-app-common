<?php
namespace Henrotaym\AccountSynchronizer;

use Henrotaym\LaravelApiClient\Client;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Henrotaym\AccountSynchronizer\Contracts\SynchronizerClientContract;

class SynchronizerClient extends Client implements SynchronizerClientContract {}