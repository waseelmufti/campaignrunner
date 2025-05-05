# CampaignRunner
**CampaignRunner** to create, manage, and run email campaigns

## Installation
- Create the *packages/waseelmufti* directory at the root of your project.
- Clone the package into that directory: 
```git clone https://github.com/waseelmufti/campaignrunner.git``` 
- Update your project's `composer.json` file to autoload the package. Add the following to the `autoload.psr-4` section:
```
"autoload": {
        "psr-4": {
            ..
            "WaseelMufti\\CampaignRunner\\": "packages/waseelmufti/campaignrunner/src/"
        }
    },
```
- Add the service provider to the `providers` array in *config/app.php*
```
 'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        ...
        WaseelMufti\CampaignRunner\CampaignRunnerServiceProvider::class,
    ])
```
- Publish the configuration file(s):
    - To publish all package files:
    ```
    php artisan vendor:publish --provider="WaseelMufti\CampaignRunner\CampaignRunnerServiceProvider"
    ```
    - To publish only the configuration file:
    ```
    php artisan vendor:publish --tag=campaignrunner-config
    ```

- Run database migrations:
```
php artisan migrate
```
