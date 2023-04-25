<?php

namespace Spatie\ResponseCache\CacheProfiles;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent as Agent;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
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
        return Sentinel::check()
            ? (string) Sentinel::getUser()->id."_".$mobile."_".session('currency')
            : '_'.$mobile."_".session('currency');
    }

    public function isRunningInConsole(): bool
    {
        if (app()->environment('testing')) {
            return false;
        }

        return app()->runningInConsole();
    }
}
