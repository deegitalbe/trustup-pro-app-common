<?php
namespace Deegitalbe\TrustupProAppCommon;

use Illuminate\Support\Collection;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\AuthenticatedUser;
use Deegitalbe\TrustupVersionedPackage\Contracts\Project\ProjectContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageContract;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\UserHavingAccessToAccount;
use Deegitalbe\TrustupProAppCommon\Exceptions\Config\NoAuthorizationKeyException;

class Package implements VersionedPackageContract
{
    /**
     * Prefix used for this package.
     * 
     * @var string
     */
    public static $prefix = "trustup_pro_app_common";

    /**
     * Account model className.
     * 
     * @return string
     */
    public function account(): string
    {
        return $this->config('account_model');
    }

    public function appKey(): string
    {
        return $this->config('app_key');
    }

    /**
     * Admin url.
     * 
     * @return string
     */
    public function adminUrl(): string
    {
        return $this->config('admin_url');
    }

    /**
     * Admin url.
     * 
     * @return string
     */
    public function trustupProUrl(): string
    {
        return $this->config('trustup_pro_url');
    }

    /**
     * Getting trustup auth header name.
     * 
     * @return string
     */
    public function trustupAuthorizationHeader(): string
    {
        return $this->config('trustup_token_header');
    }

    /**
     * Getting header defining which account should be requested.
     * 
     * @return string
     */
    public function requestedAccountHeader()
    {
        return $this->config('requested_account_header');
    }

    /**
     * Getting projects using this package.
     * 
     * @return Collection
     */
    public function getProjects(): Collection
    {
        $apps = app()->make(AdminAppApiContract::class)
            ->getAppsExceptDashboard() ?? collect();

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
        return "1.5.2";
    }

    /**
     * Getting tenancy environment.
     * 
     * @return \Hyn\Tenancy\Environment
     */
    public function environment(): \Hyn\Tenancy\Environment
    {
        return app()->make(\Hyn\Tenancy\Environment::class);
    }

    /**
     * Getting account environment middleware.
     * 
     * @param string|null $account_uuid_route_parameter if null, requested account header will be used.
     * @return array
     */
    public function accountEnvironmentMiddleware(string $account_uuid_route_parameter = null): array
    {
        return [
            ...$this->userAccountAccessMiddleware($account_uuid_route_parameter),
            SettingAccountAsEnvironment::class
        ];
    }

    /**
     * Getting user account access middleware.
     * 
     * @param string|null $account_uuid_route_parameter if null, requested account header will be used.
     * @return array
     */
    public function userAccountAccessMiddleware(string $account_uuid_route_parameter = null): array
    {
        return [
            $this->authenticatedUserMiddleware(),
            UserHavingAccessToAccount::class . ($account_uuid_route_parameter ? ":$account_uuid_route_parameter" : "")
        ];
    }

    /**
     * Getting authenticated user middleware.
     * 
     * @return string
     */
    public function authenticatedUserMiddleware(): string
    {
        return AuthenticatedUser::class;
    }

    /**
     *  Getting package name
     * 
     * @return string
     */
    public function getName(): string
    {
        return str_replace('_', '-', self::$prefix);
    }

    /**
     *  Getting package prefix.
     * 
     * @return string
     */
    public function getPrefix(): string
    {
        return self::$prefix;
    }
    
    /**
     * Getting config value.
     * 
     * @param string $key
     * @return mixed
     */
    public function config(string $key = '')
    {
        return config($this->getPrefix() . ($key ? ".$key" : ""));
    }
}