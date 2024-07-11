<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedAppUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = 'api'): Response
    {
        if (!Auth::guard($guard)->check()) {
            echo json_encode(array('type' => 'error', 'message' => 'Invalid API request. You are not logged in yet'));
            exit;
        }
        $userid = $request->header('user_id');
        if (!$userid) {
            echo json_encode(array('type' => 'error', 'message' => 'Invalid API request. User id missing.'));
            exit;
        }
        $authuserid = Auth::guard($guard)->user()->id;
        if ($userid != $authuserid) {
            echo json_encode(array('type' => 'error', 'message' => 'Invalid API request. User id does not matched.'));
            exit;
        }
        return $next($request);
    }
}
