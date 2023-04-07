<?php

namespace Spatie\ResponseCache\CacheProfiles;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent as Agent;
abstract class BaseCacheProfile implements CacheProfile
{
    public function enabled(Request $request): bool
    {
        return config('responsecache.enabled');
    }

    public function cacheRequestUntil(Request $request): DateTime
    {
        return Carbon::now()->addSeconds(
            config('responsecache.cache_lifetime_in_seconds')
        );
    }

    public function useCacheNameSuffix(Request $request): string
    {
         $Agent = new Agent();
        $mobile = $Agent->isMobile();
        return Auth::check()
            ? (string) Auth::id()."_".$mobile
            : '_'.$mobile;
    }

    public function isRunningInConsole(): bool
    {
        if (app()->environment('testing')) {
            return false;
        }

        return app()->runningInConsole();
    }
}
