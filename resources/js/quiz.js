
let dataLength = 0;

function loadNumberOfQuestions()
{
    return fetch(`http://127.0.0.1:8000/api/quizzes/${window.quizCount}/questions/count`)
    .then(function (response) {
        return response.json();
    });
}

function loadSecondsPerQuiz(id)
{
    return fetch(`http://127.0.0.1:8000/api/quizzes/${id}`)
    .then(function (response) {
        return response.json();
    });
}

async function loadQuestion(id)
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

async function loadAnswers(id)
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

function play(id, nextButton)
{
    nextButton.style.pointerEvents = "none";
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
        loadQuestion(id);
        loadAnswers(id);
        resetTimeBarProgress();
        let currentQuestion = document.getElementById("progress_bar_text").innerHTML;
        currentQuestion = currentQuestion.split("/")[0];
        currentQuestion++;
        progressBar(currentQuestion, sessionStorage.getItem("count"));
        nextButton.style.pointerEvents = "auto";
    })
    .catch((error) => {
        document.getElementById("error").innerHTML = error;
    });
}

function resetTimeBarProgress() {
    let animation = document.getElementById('time_bar_progress');
    animation.style.animation = 'none';
    animation.offsetHeight;
    animation.style.animation = null;
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
    loadQuestion(window.quizCount);
    loadAnswers(window.quizCount);
    loadSecondsPerQuiz(window.quizCount).then(function (response) {
        (response) => response.json();
        document.documentElement.style.setProperty('--quiz_seconds', response.seconds_per_quiz + "s");
        sessionStorage.setItem("header", response.header);
        const nextButton = document.getElementById("quiz_next_button");
        if (nextButton) {
            nextButton.addEventListener("click", () => play(nextButton.dataset.quizId, nextButton));
        }
    });
    loadNumberOfQuestions().then(function (response) {
        (response) => response.json();
        progressBar(1, response.count);
        sessionStorage.setItem("count", response.count);
    });
}

document.addEventListener("DOMContentLoaded", init);
