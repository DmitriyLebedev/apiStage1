<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\DbConnections;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        $db = new DbConnections;
	$conn = $db->where('user_group', Auth::user()->user_group )->first();

       	Config::set('database.connections.' . $conn->db_name, array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => $conn->db_name,
            'username'  => $conn->db_user,
            'password'  => $conn->db_pass,
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ));
 		Config::set('second_connection', $conn->db_name);

        return $next($request);
    }
}
