<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::group(['middleware' => ['api']], function () {
                Route::group(['prefix' => 'api'], function () {
                    Route::prefix('auth')
                        ->group(base_path('routes/api.php'));
                    Route::prefix('')->middleware(['auth:api', 'role:user'])
                        ->group(base_path('routes/user.php'));

                    Route::prefix('admin')->middleware(['auth:api', 'role:admin'])
                        ->group(base_path('routes/admin.php'));
                    Route::prefix('notification')->middleware(['auth:api'])
                        ->group(base_path('routes/common.php'));
                });
            });
            
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
