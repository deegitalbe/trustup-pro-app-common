<?php

namespace Deegitalbe\TrustupProAppCommon\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Providers\AppAccountServiceProvider;

/**
 *  Command used to install package.
 */
class InstallPackage extends Command
{
    /**
     * Commande signature.
     * 
     * @var string
     */
    protected $signature = 'trustup_pro_app_common:install';

    /**
     * Command description.
     * 
     * @var string
     */
    protected $description = 'Installing trustup_pro_app_common package.';

    /**
     * Handling package installation.
     * 
     * @return void
     */
    public function handle()
    {
        $this->info('Installing '. Package::getPrefix() .'...');
        $this->info('Publishing config...');
        $this->handlePublishing();
        $this->info('Installation completed.');
    }

    /**
     * Handle package config publication.
     * 
     * @return void
     */
    private function handlePublishing()
    {
        if (!$this->alreadyPublished()):
            $this->publish($this->getPublishingParams());
            return $this->info('Config published.');
        endif;
        
        if (!$overwrite = $this->confirmOverwrite()):
            return $this->info('Config was not overwritten.');
        endif;

        $this->publish($this->getPublishingParams($overwrite));
        $this->info('Config successfully overwritten.');
    }

    /**
     * Telling if config was previously published.
     * 
     * @return bool
     */
    private function alreadyPublished(): bool
    {
        return File::exists(config_path(Package::getPrefix() . '.php'));
    }

    /**
     * Asking user to confirm to overwrite.
     * 
     * @return bool
     */
    private function confirmOverwrite(): bool
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    /**
     * Getting publishing parameters.
     * 
     * @param bool $overwrite Overwrite status.
     * @return array
     */
    private function getPublishingParams(bool $overwrite = false): array
    {
        $params = [
            '--provider' => AppAccountServiceProvider::class,
            '--tag' => "config"
        ];

        if ($overwrite) {
            $params['--force'] = true;
        }

        return $params;
    }

    /**
     * Publising config with given parameters.
     * 
     * @param array $params
     */
    private function publish(array $params)
    {
        $this->call('vendor:publish', $params);
    }
}