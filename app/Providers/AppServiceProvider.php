<?php

namespace App\Providers;

use App\Channels\DatabaseChannel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());

        if ( !app()->environment('local') ) {
            URL::forceScheme('https');
        }

        JsonResource::withoutWrapping();
        // Sanctum::usePersonalAccessTokenModel(UserToken::class);
    }
}
