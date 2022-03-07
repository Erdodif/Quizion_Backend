
let dataLength = 0;

function getNumberOfQuestions()
{
    return fetch(`http://127.0.0.1:8000/api/quizzes/${window.quizCount}/questions/count`)
    .then(function (response) {
        return response.json();
    });
}

function loadDataSecondsPerQuiz(id)
{
    return fetch(`http://127.0.0.1:8000/api/quizzes/${id}`)
    .then(function (response) {
        return response.json();
    });
}

async function loadDataQuestion(id)
{
    let response = await fetch(`http://127.0.0.1:8000/api/play/${id}/question`);
    let data = await response.json();
    if (data.content == null) {
        sessionStorage.removeItem("count");
        sessionStorage.setItem("result", data.result);
        window.location = `http://127.0.0.1:8000/leaderboard/${id}`;
    }
    else {
        document.getElementById("question").innerHTML = data.content;
    }
}

async function loadDataAnswers(id)
{
    let response = await fetch(`http://127.0.0.1:8000/api/play/${id}/answers`);
    let data = await response.json();
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

function play(id, maxTime, timer)
{
    let array = idToChosen();
    const data = { chosen: array };
    fetch(`http://127.0.0.1:8000/api/play/${id}/choose`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify(data)
    })
    .then((response) => response.json())
    .then(() => {
        clearInterval(timer);
        loadDataQuestion(id);
        loadDataAnswers(id);
        let timeLeft = maxTime;
        timer = setInterval(function () {
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById("out_of_time").innerHTML = "Out of time!";
            }
            else {
                document.getElementById("time_bar").style.width = (timeLeft / maxTime) * 100 + "%";
                timeLeft -= 1;
            }
        }, 1);
        let currentQuestion = document.getElementById("progress_bar_text").innerHTML;
        currentQuestion = currentQuestion.split("/")[0];
        currentQuestion++;
        progressBar(currentQuestion, sessionStorage.getItem("count"));
    })
    .catch((error) => {
        document.getElementById("error").innerHTML = error;
    });
}

async function progressBar(currentQuestion, numberOfQuestions)
{
    document.getElementById("progress_bar_color").style.width = (currentQuestion / numberOfQuestions * 100) + "%";
    document.getElementById("progress_bar_text").innerHTML = currentQuestion + "/" + numberOfQuestions;
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
    let array = answerIds.split(" ").map((item) => item.trim());
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
    loadDataQuestion(window.quizCount);
    loadDataAnswers(window.quizCount);
    loadDataSecondsPerQuiz(window.quizCount).then(function (response) {
        (response) => response.json();
        let maxTime = response.seconds_per_quiz * 250;
        let timeLeft = maxTime;
        let timer = setInterval(function () {
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.getElementById("out_of_time").innerHTML = "Out of time!";
            }
            else {
                document.getElementById("time_bar").style.width = (timeLeft / maxTime) * 100 + "%";
                timeLeft -= 1;
            }
        }, 1);
        const nextButton = document.getElementById("quiz_next_button");
        if (nextButton) {
            nextButton.addEventListener("click", () => play(nextButton.dataset.quizId, maxTime, timer));
        }
    });
    getNumberOfQuestions().then(function (response) {
        (response) => response.json();
        progressBar(1, response.count);
        sessionStorage.setItem("count", response.count);
    });
}

document.addEventListener("DOMContentLoaded", init);
