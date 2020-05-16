<?php

namespace App\Http\Middleware;

use Closure;

class LocaleMiddleware
{
    /**
     * Handle an incoming locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->route()->parameter('locale');

        if ($request->hasHeader('X-LOCALE')) {
            $locale = $request->header('X-LOCALE');
        }

        if (session()->has('locale')) {
            $locale = session()->get('locale');
        }

        if ($locale && in_array($locale, config()->get('app.locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
