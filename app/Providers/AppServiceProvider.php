<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Workspace;
use App\Observers\UserObserver;
use App\Observers\PlanObserver;
use App\Observers\WorkspaceObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\WebhookService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Note: URL::forceRootUrl is NOT needed here.
        // Symfony's Request auto-detects /sundal as the base URL from
        // SCRIPT_NAME (/sundal/index.php), so url('/') and route() helpers
        // naturally return https://codecartz.com/sundal/... URLs.

        // Register the UserObserver
        User::observe(UserObserver::class);
        
        // Register the PlanObserver
        Plan::observe(PlanObserver::class);
        
        // Register the WorkspaceObserver
        Workspace::observe(WorkspaceObserver::class);
        


        // Configure dynamic storage disks
        try {
            \App\Services\DynamicStorageService::configureDynamicDisks();
        } catch (\Exception $e) {
            // Silently fail during migrations or when database is not ready
        }
    }
}