<?php
namespace WaseelMufti\CampaignRunner;

use Illuminate\Support\ServiceProvider;
use WaseelMufti\CampaignRunner\Contracts\CustomerRepositoryInterface;
use WaseelMufti\CampaignRunner\Repositories\CustomerRepository;
use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;
use WaseelMufti\CampaignRunner\Repositories\CampaignRepository;

class CampaignRunnerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);

    }

    public function boot()
    {
        if (file_exists(base_path('routes/campaignrunner.php'))) {
            $this->loadRoutesFrom(base_path('routes/campaignrunner.php'));
        } else {
            $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        }

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/config/campaignrunner.php' => config_path('campaignrunner.php'),
        ], 'campaignrunner-config');

        $this->publishes([
            __DIR__.'/routes/api.php' => base_path('routes/campaignrunner.php'),
        ], 'campaignrunner-routes');
    }
}
