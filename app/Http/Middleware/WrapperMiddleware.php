<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class WrapperMiddleware
{

    /**
     * Handles the return of any route, wrapping the response with its HTTP code and response,
     * eventually replacing response with exceptions text and message
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response =  $next($request);
        $code = $response->getStatusCode();
        if ($code < 400 and $code >= 200) {
            $data = $response->original;
            $response->setContent(["data" => $data]);
        } else {
            $e = $response->statusText();
            if (App::environment('local')) {
                $message = $response->exception->getMessage();
                $response->setContent(["code" => $code, "exception" => $e, "message" => $message]);
            } else {
                $response->setContent(["code" => $code, "exception" => $e]);
            }
        }
        return $response;
    }
}
