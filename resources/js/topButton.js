document.addEventListener("DOMContentLoaded", init);
function init() {
    toTopButton = document.getElementById("to-top-button");
    toTopButton.style.transition = "transition: display 300ms ease-in-out;";
    toTopButton.addEventListener("click",topFunction);
    window.onscroll = () => { scrollFunction(document.getElementById("doc-logo").offsetHeight + 100) };
}

function scrollFunction(height) {
    if (document.body.scrollTop > height || document.documentElement.scrollTop > height) {
        toTopButton.style.opacity = "1";
        toTopButton.style.bottom = "1em";
    } else {
        toTopButton.style.opacity = "0";
        toTopButton.style.bottom = "-4em";
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}