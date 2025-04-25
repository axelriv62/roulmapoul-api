<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\Amendment;
use App\Models\Customer;
use App\Models\Handover;
use App\Models\Rental;
use App\Models\Withdrawal;
use App\Policies\AmendmentPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\HandoverPolicy;
use App\Policies\RentalPolicy;
use App\Policies\WithdrawalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole(Role::ADMIN->value) ? true : null;
        });

        Gate::policy(Rental::class, RentalPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Handover::class, HandoverPolicy::class);
        Gate::policy(Withdrawal::class, WithdrawalPolicy::class);
        Gate::policy(Amendment::class, AmendmentPolicy::class);
    }
}
