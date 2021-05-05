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

        $this->app->bind(
            'App\Http\Interfaces\SessionInterface',
            'App\Http\Repositories\SessionRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\SupscriptionInterface',
            'App\Http\Repositories\SupscriptionRepository'
        );


        $this->app->bind(
            'App\Http\Interfaces\ComplaintInterface',
            'App\Http\Repositories\ComplaintRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\EndUserInterface',
            'App\Http\Repositories\EndUserRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\ExamInterface',
            'App\Http\Repositories\ExamRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\StudentExamInterface',
            'App\Http\Repositories\StudentExamRepository'
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
