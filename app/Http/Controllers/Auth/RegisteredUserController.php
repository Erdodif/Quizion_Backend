<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        $user = User::addNew([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ])->getDataRaw();

        event(new Registered($user));

        $input = ["userID" => $request->input("email"), "password" => $request->input("password")];

        $result = Token::addNewByLogin($input);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->cookie(cookie('token', $result->getDataRaw()->getContent()[1], secure: true));
    }
}
