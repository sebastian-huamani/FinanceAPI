<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Card;
use App\Models\Item;
use App\Models\Template;
use App\Policies\CardPolicy;
use App\Policies\ItemPolicy;
use App\Policies\TemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
