
let dataLength = 0;

function loadDataSecondsPerQuiz(id)
{
    /*
    let Response = await fetch(`http://127.0.0.1:8000/api/quizzes/${id}`);
    let data = await Response.json();
    return data.seconds_per_quiz * 250;
    */
    return fetch(`http://127.0.0.1:8000/api/quizzes/${id}`)
    .then((response) => response.json())
    .then((data) => {
      return data.seconds_per_quiz * 250;
    })
    .catch(error => {
        document.getElementById("error").innerHTML = error;
    });
}

async function loadDataQuestion(id)
{
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/question`);
    let data = await Response.json();
    if (data.content == null) {
        sessionStorage.setItem("result", data.result);
        window.location = `http://127.0.0.1:8000/leaderboard/${id}`;
    }
    else {
        document.getElementById("question").innerHTML = data.content;
    }
}

async function loadDataAnswers(id)
{
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/answers`);
    let data = await Response.json();
    document.getElementById("answers").innerHTML = "";
    dataLength = Object.keys(data).length;
    for (let i = 0; i < dataLength; i++) {
        let answer = document.createElement("div");
        answer.innerHTML = data[i].content;
        answer.classList.add("quiz_answer");
        answer.setAttribute("id", "answer" + (i + 1) + " " + data[i].id);
        answer.addEventListener("click", () => answerOnClick("answer" + (i + 1) + " " + data[i].id));
        document.getElementById("answers").appendChild(answer);
    }
}

async function play(id)
{
    let array = idToChosen();
    const data = { "chosen": array };
    await fetch(`http://127.0.0.1:8000/api/play/${id}/choose`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(() => {
        window.timer.reset(1);
        loadDataQuestion(window.quizCount);
        loadDataAnswers(window.quizCount);
    })
    .catch(error => {
        document.getElementById("error").innerHTML = error;
    });
}

function Timer(fn, t) {
    let timerObj = setInterval(fn, t);
    this.stop = function() {
        if (timerObj) {
            clearInterval(timerObj);
            timerObj = null;
        }
        return this;
    }
    this.start = function() {
        if (!timerObj) {
            this.stop();
            timerObj = setInterval(fn, t);
        }
        return this;
    }
    this.reset = function(newT = t) {
        t = newT;
        return this.stop().start();
    }
}

function setInvertalFunction(maxTime, timeLeft) {
    if (timeLeft <= 0) {
        window.timer.stop();
        document.getElementById("out_of_time").innerHTML = "Out of time!";
    }
    else {
        document.getElementById("time_bar").style.width = timeLeft / maxTime * 100 + "%";
    }
    timeLeft -= 1;
}

function idToChosen()
{
    let answerIds = "";
    for (let i = 0; i < dataLength; i++) {
        try {
            answerIds += document.getElementsByClassName("selected")[i].id + " ";
        }
        catch (error) {}
    }
    answerIds = answerIds.trim();
    let array = answerIds.split(" ").map(item => item.trim());
    for (let i = array.length - 1; i >= 0; i--) {
        if (array[i].includes("answer")) {
            array.splice(i, 1);
        }
    }
    array = array.map(Number);
    return array;
}

function answerOnClick(selectedAnswerId)
{
    document.getElementById(selectedAnswerId).classList.toggle("selected");
}

function init()
{
    //JAVÍT maxTime
    let maxTime = loadDataSecondsPerQuiz(window.quizCount).then(response => {return response;});
    let timeLeft = maxTime;
    window.timer = new Timer(setInvertalFunction(maxTime, timeLeft), 1);
    window.timer.start();
    loadDataQuestion(window.quizCount);
    loadDataAnswers(window.quizCount);
    const nextButton = document.getElementById("quiz_next_button");
    if (nextButton) {
        nextButton.addEventListener("click", () => play(nextButton.dataset.quizId));
    }
}

document.addEventListener("DOMContentLoaded", init);
