<?php
namespace Henrotaym\AccountSynchronizer\Providers;

use Illuminate\Support\ServiceProvider;
use Henrotaym\AccountSynchronizer\ClientCredential;
use Henrotaym\AccountSynchronizer\SynchronizerClient;
use Henrotaym\AccountSynchronizer\Observers\AccountObserver;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Henrotaym\AccountSynchronizer\Contracts\SynchronizerClientContract;

class AppAccountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SynchronizerClientContract::class, function($app) {
            return new SynchronizerClient(new ClientCredential);
        });

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