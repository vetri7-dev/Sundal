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
        // Force the URL generator to use APP_URL as the root.
        // This is essential on shared hosting subdirectory deployments
        // so that all route() and url() calls include the /sundal/ prefix.
        $appUrl = config('app.url');
        if ($appUrl) {
            URL::forceRootUrl($appUrl);
            if (str_starts_with($appUrl, 'https')) {
                URL::forceScheme('https');
            }
        }

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