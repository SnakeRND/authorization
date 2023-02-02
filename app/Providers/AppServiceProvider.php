<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/JWTCheckHelper.php';
        require_once app_path() . '/Helpers/Error.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (str_starts_with(env('APP_URL'), 'https')) {
            URL::forceScheme('https');
        }

        $this->registerHttpMonolithMacro();
        $this->registerEnterprise1CMacro();
        $this->registerEnterprise1CWebCCMacro();
        $this->registerIboxMacro();
        $this->registerYandexMacro();
    }

    private function registerHttpMonolithMacro(): void
    {
        Http::macro('monolith', function () {
            $client = Http::baseUrl('https://uvi.ru/api')->withToken(env('UVI_MONOLITH_TOKEN'));
            if (! is_null(env('UVI_MONOLITH_BRANCH'))) {
                $client = $client->withHeaders(['Cookie' => 'branch=' . env('UVI_MONOLITH_BRANCH')]);
            }
            return $client;
        });
    }

    private function registerIboxMacro(): void
    {
        Http::macro('ibox', function () {
            return Http::baseUrl(env('IBOX_URL'))->withBasicAuth(env("IBOX_LOGIN"), env("IBOX_PASS"));
        });
    }

    private function registerEnterprise1CMacro(): void
    {
        Http::macro('enterprise1C', function () {
            return Http::baseUrl(env("API_1C"))->withBasicAuth(env('API_1C_USER'), env("API_1C_PASS"));
        });
    }

    private function registerEnterprise1CWebCCMacro(): void
    {
        Http::macro('enterprise1CWebCC', function () {
            return Http::baseUrl(env("API_1C"))->withBasicAuth(env('API_1C_USER_WEBCC'), env("API_1C_PASS_WEBCC"));
        });
    }

    private function registerYandexMacro(): void
    {
        Http::macro('yandex', function () {
            return Http::baseUrl(env('YANDEX_URL'))->withToken(env('YANDEX_TOKEN'));
        });
    }
}
