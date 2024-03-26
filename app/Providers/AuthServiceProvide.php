<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Post;
use App\Policies\ExpensePolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Expense::class => ExpensePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('expense', ExpensePolicy::class);
    }
}