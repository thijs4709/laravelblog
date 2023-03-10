<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /*7.3 en lager php*/
        Gate::define('admin',function (User $user){
            return $user->isAdmin(); //retourneert of je een adminstrator bent of niet?
        });
        /*vanaf php 7.4 en hoger*/ //laravel kan met de lagere versie overweg in de hogere
//        Gate::define('admin', fn() => $user->isAdmin());
        //
    }
}
