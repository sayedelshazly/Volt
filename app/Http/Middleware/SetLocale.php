<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class SetLocale {
    public function handle(Request $request, Closure $next) {
        $l = $request->cookie('locale', 'ar');
        app()->setLocale(in_array($l, ['ar', 'en'], true) ? $l : 'ar');
        return $next($request);
    }
}
