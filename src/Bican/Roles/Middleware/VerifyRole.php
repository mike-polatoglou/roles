<?php

namespace Bican\Roles\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Bican\Roles\Exceptions\RoleDeniedException;

class VerifyRole
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     * @throws RoleDeniedException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        foreach($roles as $role) {
            if ($this->auth->check() && $this->auth->user()->hasRole($role)) {
                return $next($request);
            }
        }

        throw new RoleDeniedException($role);
    }
}