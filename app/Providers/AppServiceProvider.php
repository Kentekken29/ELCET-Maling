<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        $hotFile = public_path('hot');

        if (! is_file($hotFile)) {
            return;
        }

        $hotUrl = @file_get_contents($hotFile);
        $hotUrlParts = is_string($hotUrl) ? parse_url(trim($hotUrl)) : false;

        if (
            ! is_array($hotUrlParts)
            || ! isset($hotUrlParts['scheme'], $hotUrlParts['host'])
            || ! in_array($hotUrlParts['scheme'], ['http', 'https'], true)
        ) {
            Vite::useHotFile(storage_path('framework/vite-offline-hot'));
            Log::warning('The Vite hot file is invalid; built assets will be used.');

            return;
        }

        $port = $hotUrlParts['port'] ?? ($hotUrlParts['scheme'] === 'https' ? 443 : 80);
        $socket = @fsockopen($hotUrlParts['host'], $port, $errorCode, $errorMessage, 0.15);

        if (is_resource($socket)) {
            fclose($socket);

            return;
        }

        Vite::useHotFile(storage_path('framework/vite-offline-hot'));
        Log::debug('The Vite development server is unavailable; built assets will be used.', [
            'host' => $hotUrlParts['host'],
            'port' => $port,
            'error_code' => $errorCode,
            'error' => $errorMessage,
        ]);
    }
}
