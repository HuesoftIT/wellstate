<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Image;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\RoomType;
use App\Models\Service;
use App\Observers\BranchObserver;
use App\Observers\EmployeeObserver;
use App\Observers\PostObserver;
use App\Observers\PromotionObserver;
use App\Observers\ImageObserver;
use App\Observers\RoomTypeObserver;
use App\Observers\ServiceObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Knuckles\Scribe\Scribe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Register observer
        Service::observe(ServiceObserver::class);
        Promotion::observe(PromotionObserver::class);
        Post::observe(PostObserver::class);
        Image::observe(ImageObserver::class);
        Branch::observe(BranchObserver::class);
        RoomType::observe(RoomTypeObserver::class);
        Employee::observe(EmployeeObserver::class);

        \Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        if (class_exists(\Knuckles\Scribe\Scribe::class)) {
            Scribe::beforeResponseCall(function (Request $request, ExtractedEndpointData $endpointData) {
                $token = User::first()->api_token;
                $request->headers->add(["Authorization" => "Bearer $token"]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
