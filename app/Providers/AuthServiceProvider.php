<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

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

        Gate::define('moderator', function (User $user) {
            return $user->role === 'moderator';
        });

        Gate::define('operator', function (User $user) {
            $office = Office::where('user_id', auth()->user()->id)->first();
            return $user->role === 'operator' && $office != null;
        });
    }
}
