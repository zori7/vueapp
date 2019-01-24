<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Post' => 'App\Policies\PostPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Answer' => 'App\Policies\AnswerPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('posts', 'App\Policies\PostPolicy');
        Gate::resource('comments', 'App\Policies\CommentPolicy');
        Gate::resource('answers', 'App\Policies\AnswerPolicy');
        Gate::define('all', function () {
            return Auth::user()->isAdmin()['isAdmin'];
        });
    }
}
