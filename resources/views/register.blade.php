@extends("layouts.app")

@section("title", "Quizion")

@section("content")
<form method="POST">
    <div>
        <label for="username_id">Felhasználónév</label>
        <input type="text" id="username_id" name="username" value="">
        <div class="error_message"></div>
    </div>
    <div>
        <label for="email_id">Email cím</label>
        <input type="email" id="email_id" name="email" value="">
        <div class="error_message"></div>
    </div>
    <div>
        <label for="password1_id">Jelszó</label>
        <input type="password" id="password1_id" name="password1" value="">
        <div class="error_message"></div>
    </div>
    <div>
        <label for="password2_id">Jelszó még egyszer</label>
        <input type="password" id="password2_id" name="password2" value="">
        <div class="error_message"></div>
    </div>
    <input type="submit" name="click" value="Regisztráció">
</form>
<a href="index">Vissza</a>
@endsection
