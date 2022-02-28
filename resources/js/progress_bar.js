let maxTime = 1000;
let timeLeft = 1000;
let timer = setInterval(function() {
    if (timeLeft <= 0) {
        clearInterval(timer);
        document.getElementById("out_of_time").innerHTML = "Lejárt az idő!";
    }
    else {
        document.getElementById("time_bar").style.width = timeLeft / maxTime * 100 + "%";
    }
    timeLeft -= 1;
}, 1);
