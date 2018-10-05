<?php

namespace Cloudstudio\ResourceGenerator\Http\Middleware;

use Closure;
use Cloudstudio\ResourceGenerator\ResourceGenerator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return app(ResourceGenerator::class)->authorize($request)
        ? $next($request)
        : abort(403);
    }
}
