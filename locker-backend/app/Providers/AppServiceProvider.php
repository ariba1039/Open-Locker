<?php

namespace App\Providers;

use App\Services\FakeLockerService;
use App\Services\LockerServiceInterface;
use Carbon\CarbonImmutable;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LockerServiceInterface::class, function ($app) {
            return new FakeLockerService; // or new RealLockerService() in the Future
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });

        // Use CarbonImmutable for all date instances. Prevents date mutability.
        Date::use(CarbonImmutable::class);

        // Removes wrapping of JSON responses.
        JsonResource::withoutWrapping();
    }
}
