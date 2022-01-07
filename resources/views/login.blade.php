@extends("layouts.app")

@section("title", "Quizion")

@section("content")
<form method="POST">
    <div>
        <label for="username_email">Felhasználónév vagy email cím</label>
        <input type="text" id="username_email" name="username_email" value="">
    </div>
    <div>
        <label for="password">Jelszó</label>
        <input type="password" id="password" name="password" value="">
    </div>
    <input type="submit" value="Bejelentkezés">
    <div class="error_message"></div>
</form>
<a href="index">Vissza</a>
@endsection
