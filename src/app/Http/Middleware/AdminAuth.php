<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param array                     $guards
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // 验证是否已经登录
        if (Auth::guard('admin')->guest()) {
            return $this->handleUnauthorized();
        }

        Auth::shouldUse($guards ?: 'admin');

        // 验证权限
        if (config('admin.verify_permissions') == true && !Auth::user()->can(trim(request()->getPathInfo(), '/'))) {
            return $this->handleUnauthorized();
        }

        return $next($request);
    }

    /**
     * 没有权限处理
     * 
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function handleUnauthorized()
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', 401);
        }

        return redirect()->guest('admin/login/index');
    }
}
