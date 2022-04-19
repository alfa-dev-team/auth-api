## Installation

Add dependency using composer
`composer require alfa-dev-team/auth-api`

You need to add configuration and migrations using the command:<br>
`php artisan vendor:publish --tag=configs`<br>
`php artisan vendor:publish --tag=migrations`

In the `config` directory, find the `auth-api.php` file and add your custom
models.

`composer require doctrine/dbal`

Run migrations 
`php artisan migrate`

Add routes using<br>
`AuthApi::authRoutes();`<br>
`AuthApi::settingsRoutes();`

If you want to redefine some controller, then create a controller that will also
inherit from the controller that you are redefining.
And add to your service provider in `register` method
For example:

`$this->app->bind(AlfaDevTeam\AuthApi\Controllers\ApiController\RegisterController::class, function ($app){
return new \Modules\Cabinet\Http\Controllers\Auth\RegisterController();
});`