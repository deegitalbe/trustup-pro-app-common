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
        $this->registerConfig();

        $this->app->bind(SynchronizerClientContract::class, function($app) {
            return new SynchronizerClient(new ClientCredential);
        });
        $this->app->bind(SynchronizerContract::class, Synchronizer::class);
    }

    public function boot()
    {
        $this->makeConfigPublishable();
    }

    protected function registerConfig(): self
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'trustup_pro_app_common');

        return $this;
    }

    protected function makeConfigPublishable(): self
    {
        if ($this->app->runningInConsole()):
            $this->publishes([
              $this->getConfigPath() => config_path('trustup_pro_app_common.php'),
            ], 'config');
        endif;

        return $this;
    }

    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}