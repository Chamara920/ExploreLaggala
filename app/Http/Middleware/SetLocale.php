<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['si', 'en', 'ta'];

        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales, true)) {
            session(['locale' => $request->query('lang')]);
        }

        $locale = session('locale', config('app.locale', 'en'));

        if (in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
