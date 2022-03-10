<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class QueryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        DB::connection()->enableQueryLog();
        $response =  $next($request);
        if (App::environment('local')) {
            $myLog = DB::getQueryLog();
            $obj = ["queryLog" => $myLog, "response" => $response->original];
            $response->setContent($obj);
        }
        return $response;
    }
}
