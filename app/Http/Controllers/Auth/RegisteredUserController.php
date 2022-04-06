<?php

namespace App\Http\Controllers\Auth;

use App\Companion\ResponseCodes;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Models\Token;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        $result = UserController::addNew($request);

        if (!$result->getCode(ResponseCodes::RESPONSE_CREATED)) {
            return redirect('auth.register', ["userError" => "Registration failed!"]);
        }

        $user = $result->getDataRaw();

        event(new Registered($user));

        $input = ["userID" => $request->input("email"), "password" => $request->input("password")];

        $result = Token::addNewByLogin($input);

        Auth::login($user);

        return redirect('/verify-email')->cookie(cookie('token', $result->getDataRaw()->getContent()[1], 3000000, secure: true));
    }
}
