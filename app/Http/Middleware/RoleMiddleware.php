<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, string ...$roles) {
        $u = $request->user();
        abort_unless($u && in_array($u->role, $roles, true), 403, 'Forbidden');
        return $next($request);
    }
}
