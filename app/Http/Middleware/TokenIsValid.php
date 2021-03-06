<?php

namespace App\Http\Middleware;

use App\Companion\Data;
use App\Companion\Message;
use App\Companion\ResponseCodes;
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
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken() ?? $request->cookie('token');
        if (empty($token)) {
            return (new Data(
                ResponseCodes::ERROR_UNAUTHORIZED,
                new Message("Login required!")
            ))->toResponse();
        }
        $result = Token::getTokenByKey($token);
        if (!$result) {
            return (new Data(
                ResponseCodes::ERROR_UNAUTHORIZED,
                new Message("Invalid or expired token!")
            ))->toResponse();
        }
        $request->userID = $result->user_id;
        return $next($request);
    }
}
