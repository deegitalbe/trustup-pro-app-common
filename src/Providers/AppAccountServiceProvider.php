<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Illuminate\Support\ServiceProvider;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Deegitalbe\TrustupProAppCommon\ClientCredential;
use Deegitalbe\TrustupProAppCommon\SynchronizerClient;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\Observers\AccountObserver;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerClientContract;

class AppAccountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SynchronizerClientContract::class, function($app) {
            return new SynchronizerClient(new ClientCredential);
        });
        $this->app->bind(SynchronizerContract::class, Synchronizer::class);

        $this->registerConfig();
    }

    public function boot()
    {
        $this->makeConfigPublishable();
    }

    protected function registerConfig(): self
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'account_synchronizer');

        return $this;
    }

    protected function makeConfigPublishable(): self
    {
        if ($this->app->runningInConsole()):
            $this->publishes([
              $this->getConfigPath() => config_path('account_synchronizer.php'),
            ], 'config');
        endif;

        return $this;
    }

    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}