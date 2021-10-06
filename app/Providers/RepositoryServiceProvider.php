<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register Interface and Repository in here
        // You must place Interface in first place
        // If you dont, the Repository will not get readed.
        $this->app->bind(
            'App\Interfaces\UserInterface',
            'App\Repositories\UserRepository'
        );

        $this->app->bind(
            'App\Interfaces\ProfileInterface',
            'App\Repositories\ProfileRepository'
        );

        $this->app->bind(
            'App\Interfaces\MailInterface',
            'App\Repositories\MailRepository'
        );

        $this->app->bind(
            'App\Interfaces\PasswordInterface',
            'App\Repositories\PasswordRepository'
        );

        $this->app->bind(
            'App\Interfaces\OfferInterface',
            'App\Repositories\OfferRepository'
        );

        $this->app->bind(
            'App\Interfaces\VideoInterface',
            'App\Repositories\VideoRepository'
        );

        $this->app->bind(
            'App\Interfaces\OrderInterface',
            'App\Repositories\OrderRepository'
        );

        $this->app->bind(
            'App\Interfaces\IncomeInterface',
            'App\Repositories\IncomeRepository'
        );

        $this->app->bind(
            'App\Interfaces\Admin\AdminUsersInterface',
            'App\Repositories\Admin\AdminUsersRepository'
        );
    }
}