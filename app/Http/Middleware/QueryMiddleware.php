<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class QueryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //can activate middleware before passing the request
        //echo "before passing the request!", "<br>";
        //return $next($request);

        // or maybe
        DB::connection()->enableQueryLog();
        $response =  $next($request);
        $myLog = DB::getQueryLog();
        $obj = Array("log" => $myLog, "response" => $response);
        return $obj;

    }
}
