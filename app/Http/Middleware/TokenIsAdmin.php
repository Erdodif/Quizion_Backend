<?php

namespace App\Http\Middleware;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class TokenIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Használat csak a TokenIsValid midlleware-el együtt(biztonsági okokból!)
        $id = $request->userID;
        if(!Admin::isAdmin($id)){
            return (new Data(
                ResponseCodes::ERROR_FORBIDDEN,
                new Message("You have no permission to perform this action!")
            ))->toResponse();
        }
        return $next($request);
    }
}
