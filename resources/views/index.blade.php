@extends("layouts.app")

@section("title", "- Index")

@section("content")
<script src="{{ mix('js/topButton.js') }}"></script>
<div class="landing">
    <div class="image-holder">
        <img id="doc-logo" src="{{ url('images/logo.png') }}">
    </div>
    <div class="sub-nav">
        <ul>
            <li>
                <a href="{{route('documentation',['page'=>'introduction'])}}">Bevezetés</a>
            </li>
            <li>
                <a href="{{route('documentation',['page'=>'topic'])}}">Témaválasztás</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <?php
        if (isset($page) && $page !== null) {
            try {
                $out = require(resource_path('static_pages\\' . $page . '.html'));
            } catch (Exception | Error $e) {
                $out = require(resource_path('static_pages\\not_found.php'));
            }
        } else {
            $out = require(resource_path('static_pages\\introduction.html'));
        }
        ?>
    </div>
    <div id="to-top-button">
        Vissza a tetejére
    </div>
</div>
@endsection