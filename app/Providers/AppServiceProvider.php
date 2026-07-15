<?php

namespace App\Providers;

use App\Domain\Chaturbate\Clients\ChaturbateClient;
use App\Domain\Chaturbate\Clients\HttpChaturbateClient;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChaturbateClient::class, function (): HttpChaturbateClient {
            return new HttpChaturbateClient(
                baseUrl: (string) config('services.chaturbate.base_url'),
                endpoint: (string) config('services.chaturbate.endpoint'),
                limit: (int) config('services.chaturbate.limit'),
                timeout: (int) config('services.chaturbate.timeout'),
                connectTimeout: (int) config('services.chaturbate.connect_timeout'),
                retryAttempts: (int) config('services.chaturbate.retry_attempts'),
                retrySleepMilliseconds: (int) config('services.chaturbate.retry_sleep_milliseconds'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
