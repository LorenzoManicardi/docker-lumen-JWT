<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WrapperMiddleware
{

    /**
     * Handles the return of any route, wrapping the response with its HTTP code and response,
     * eventually replacing response with exceptions text and message
     * @param Request $request
     * @param Closure $next
     * @return array
     */
    public function handle(Request $request, Closure $next): array
    {
        $response =  $next($request);
        $code = $response->getStatusCode();
        if ($code < 400 and $code >= 200) {
            $data = $response->original;
            return ["data" => $data];
        } else {
            $e = $response->statusText();
            $message = $response->exception->getMessage();
            return ["code" => $code, "exception" => $e, "message" => $message];
        }
    }
}
