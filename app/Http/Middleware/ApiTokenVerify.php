<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // please provide this token from table oauth_clients have provider = users and password_client = 1
        $token = $request->bearerToken();
        if (!$token) {
            abort(401, 'Unauthorized');
        }

        $checkToken = DB::table('oauth_clients')->where('secret', $token)->first();
        if (!$checkToken) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}
