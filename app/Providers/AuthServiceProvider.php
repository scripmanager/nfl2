<?php

namespace App\Providers;

use App\Models\Entry;
use App\Policies\EntryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Entry::class => EntryPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}