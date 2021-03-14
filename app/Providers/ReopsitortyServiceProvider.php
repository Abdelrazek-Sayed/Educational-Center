<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ReopsitortyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\AuthInterface',
            'App\Http\Repositories\AuthRepository'
        );


        $this->app->bind(
            'App\Http\Interfaces\StaffInterface',
            'App\Http\Repositories\StaffRepository'
        );


        $this->app->bind(
            'App\Http\Interfaces\teacherInterface',
            'App\Http\Repositories\teacherRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\groupInterface',
            'App\Http\Repositories\groupRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\StudentInterface',
            'App\Http\Repositories\StudentRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
