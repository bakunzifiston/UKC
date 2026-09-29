<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAdminRoute
{
    /**
     * @var array<string, string>
     */
    private const ACTIONS = [
        'index' => 'view',
        'show' => 'view',
        'create' => 'create',
        'store' => 'create',
        'edit' => 'update',
        'update' => 'update',
        'destroy' => 'delete',
        'active' => 'update',
        'password' => 'update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $permission = $this->permissionFor($request->route()?->getName());
        $user = $request->user();

        if ($permission !== null && ($user === null || ! $user->can($permission))) {
            abort(403);
        }

        return $next($request);
    }

    private function permissionFor(?string $name): ?string
    {
        if ($name === null || $name === 'admin.logout') {
            return null;
        }

        if ($name === 'admin.dashboard') {
            return 'dashboard.view';
        }

        if (! preg_match('/^admin\.([a-z0-9\-]+)\.([a-z]+)$/', $name, $matches)) {
            return null;
        }

        [, $resource, $action] = $matches;

        if ($resource === 'system') {
            return 'system.configure';
        }

        $verb = self::ACTIONS[$action] ?? null;

        return $verb === null ? null : $resource.'.'.$verb;
    }
}
