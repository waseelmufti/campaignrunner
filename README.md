# 📢 CampaignRunner

**CampaignRunner** is a Laravel package to create, manage, and run email campaigns.

## 🛠️ Installation
1.  Clone the Package \
Create the packages/waseelmufti directory at the root of your Laravel project, then clone the package into it:
    ```
    git clone https://github.com/waseelmufti/campaignrunner.git packages/waseelmufti/campaignrunner
    ```
2. Configure Composer Autoloading \
Update your project's composer.json to autoload the package. In the autoload.psr-4 section, add:
    ```
    "autoload": {
        "psr-4": {
            ...
            "WaseelMufti\\CampaignRunner\\": "packages/waseelmufti/campaignrunner/src/"
        }
    }
    ```
    Also, register the package as a local dependency by adding this to the `repositories` section:
    ```
    "repositories": [
        {
            "type": "path",
            "url": "packages/waseelmufti/campaignrunner"
        }
    ]
    ```
    Then, install the package:
    ```
    composer require waseelmufti/campaignrunner
    ```

3. Register the Service Provider \
In `config/app.php`, add the service provider inside the providers array:
    ```
    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
        * Package Service Providers...
        */
        ...
        WaseelMufti\CampaignRunner\CampaignRunnerServiceProvider::class,
    ])
    ```


4. Publish Package Files \
    To publish all package resources:
    ```
    php artisan vendor:publish --provider="WaseelMufti\CampaignRunner\CampaignRunnerServiceProvider"
    ```
    To publish only the configuration file:
    ```
    php artisan vendor:publish --tag=campaignrunner-config
    ```
5. Run Migrations \
    Run the following to create necessary tables:

    ```
    php artisan migrate
    ```


6. Enable Email Queueing \
    To process campaign emails asynchronously via Laravel's queue system:
    - Generate the queue table:
    ```
    php artisan queue:table
    php artisan migrate
    ```
    - In your .env file, set the queue connection to database:
    ```
    QUEUE_CONNECTION=database
    ```
    - Run the queue worker:
    ```
    php artisan queue:work
    ```

## 📬 Postman Collection

A Postman collection is included in the package to help you test the available API endpoints easily.

You can find the collection file inside the package directory:
```
packages/waseelmufti/campaignrunner/Postman/CampaignRunner.postman_collection.json
```
To use it:
- Open Postman.
- Click on Import.
- Choose the collection file from the path above.
- You can now test all available campaign APIs directly from Postman.
