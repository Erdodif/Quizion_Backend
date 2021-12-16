<?php

namespace App\Http\Middleware;

use App\Companion\Data;
use App\Companion\Message;
use App\Models\Token;
use Closure;
use Illuminate\Http\Request;

class TokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $token = Token::getTokenByKey($request->bearerToken());
        if (!$token){
            return (new Data(
                    ERROR_UNAUTHORIZED,
                    new Message("Login required!")
                ))->toResponse;
        }
        $request->attributes->add(["userID"=>$token->user_id]);
        return $next($request);
    }
}
