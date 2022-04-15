<?php
$url = "http";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $url .= "s";
}
$url .= '://';
$url .= $_SERVER['HTTP_HOST'] . "/";
$subs = explode($_SERVER['REQUEST_URI'], "/");
for ($i = 0; $i < count($subs) - 2; $i++) {
    $url .= $subs[$i] . "/";
};
?>
<div>
    <h1>
        Oldal nem található!
    </h1>
    <a href="<?php echo $url; ?>">
        <div class="doc-button">
            Vissza a főoldalra
        </div>
    </a>
</div>