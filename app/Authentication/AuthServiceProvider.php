<?php
namespace App\Authentication;
use Auth;
use App\Authentication\UserProvider;
use Illuminate\Support\ServiceProvider;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('LSVRP_provider', function($app, array $config) {
            return new UserProvider();
        });
    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}