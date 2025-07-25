<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ExtendedPermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if(!isAdmin()) {
            $authGuard = app('auth')->guard($guard);

            if ($authGuard->guest()) {
                throw UnauthorizedException::notLoggedIn();
            }

            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);

            foreach ($permissions as $permission) {
                if ($authGuard->user()->can($permission)) {
                    return $next($request);
                }
            }

            throw UnauthorizedException::forPermissions($permissions);
        } else {
            return $next($request);
        }
    }
}
