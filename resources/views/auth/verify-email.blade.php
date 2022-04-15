@extends("layouts.app")

@section("title", "Verify Email")

@section("content")
    <script src="{{ mix('js/form.js') }}"></script>
    <div class="verify-email-info" id="padding-block">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</div>
    @if (session('status') == 'verification-link-sent')
        <div class="verify-email-info">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <input id="button-one-click" type="submit" value="{{ __('Resend Verification Email') }}">
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" value="{{ __('Logout') }}">
    </form>
@endsection
