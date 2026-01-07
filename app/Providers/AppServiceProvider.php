<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingNeedsAssessment;

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
            View::composer('layouts.app', function ($view) {
                $activeTab = 'questions';
                $responsesCount = 0;
                
                if (Auth::check()) {
                    // Only count responses that have both Part A and Part B completed
                    $responsesCount = TrainingNeedsAssessment::where('part_a_submitted', true)
                        ->where('part_b_submitted', true)
                        ->count();
                    
                    // Determine active tab based on current route
                    $routeName = request()->route()->getName();
                    if (str_contains($routeName, 'questions')) {
                        $activeTab = 'questions';
                    } elseif (str_contains($routeName, 'responses')) {
                        $activeTab = 'responses';
                    } elseif (str_contains($routeName, 'report')) {
                        $activeTab = 'report';
                    } elseif (str_contains($routeName, 'settings')) {
                        $activeTab = 'settings';
                    }
                }
                
                $view->with([
                    'activeTab' => $activeTab,
                    'responsesCount' => $responsesCount,
                ]);
            });
    }
}
