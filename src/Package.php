<?php
namespace Deegitalbe\TrustupProAppCommon;

use Illuminate\Support\Collection;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\Project\ProjectContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\Config\NoAuthorizationKeyException;

class Package implements VersionedPackageContract
{
    /**
     * Account model className.
     * 
     * @return string
     */
    public function account(): string
    {
        return config('trustup_pro_app_common.account_model');
    }

    /**
     * Admin url.
     * 
     * @return string
     */
    public function adminUrl(): string
    {
        return config('trustup_pro_app_common.admin_url');
    }

    /**
     * Getting projects using this package.
     * 
     * @return Collection
     */
    public function getProjects(): Collection
    {
        $apps = app()->make(AdminAppApiContract::class)
            ->getApps() ?? collect();

        return $apps
                ->filter(function($app) {
                    return $app->url !== config('app.url');
                })
                ->map(function($app) {
                    return app()->make(ProjectContract::class)
                        ->setUrl($app->url)
                        ->setVersionedPackage($this);
                });
    }

    /**
     * Getting package version.
     */
    public function getVersion(): string
    {
        return "1.1.0";
    }

    /**
     *  Getting package name
     * 
     * @return string
     */
    public function getName(): string
    {
        return "trustup-pro-app-common";
    }
}