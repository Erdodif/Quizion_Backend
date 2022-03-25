
let dataLength = 0;

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
    return array[0] != "" ? array.map(Number) : false;
}

function answerOnClick(selectedAnswerId)
{
    document.getElementById(selectedAnswerId).classList.toggle("selected");
}

function progressBar(currentQuestion, numberOfQuestions)
{
    document.getElementById("progress_bar_color").style.width = (currentQuestion / numberOfQuestions * 100) + "%";
    document.getElementById("progress_bar_text").innerHTML = currentQuestion + "/" + numberOfQuestions;
}

function nextProgressBar() {
    let currentQuestion = document.getElementById("progress_bar_text").innerHTML;
    currentQuestion = currentQuestion.split("/")[0];
    currentQuestion++;
    progressBar(currentQuestion, sessionStorage.getItem("count"));
}

function resetTimeBarProgress() {
    let animation = document.getElementById("time_bar_progress");
    animation.style.animation = "none";
    animation.offsetHeight;
    animation.style.animation = null;
}

function showQuestion(question) {
    document.getElementById("question").innerHTML = question;
}

function showAnswers(answers) {
    dataLength = Object.keys(answers).length;
    document.getElementById("answers").innerHTML = "";
    for (let i = 0; i < dataLength; i++) {
        let answer = document.createElement("div");
        answer.innerHTML = answers[i].content;
        answer.classList.add("quiz_answer");
        answer.setAttribute("id", "answer" + (i + 1) + " " + answers[i].id);
        answer.addEventListener("click", () => answerOnClick("answer" + (i + 1) + " " + answers[i].id));
        document.getElementById("answers").appendChild(answer);
    }
}

function play(id, nextButton)
{
    nextButton.style.pointerEvents = "none";
    let array = idToChosen();
    if (array) {
        const data = { chosen: array };
        fetch(`${window.url}/api/play/${id}/choose`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(data)
        })
        .then((response) => {
            if (response.ok) {
                nextQuestion(id, nextButton);
            }
        })
        .catch((error) => {
            document.getElementById("error").innerHTML = error;
        });
    }
    else {
        nextButton.style.pointerEvents = "auto";
    }
}

function nextQuestion(id, nextButton) {
    fetch(`${window.url}/api/play/${id}/question`)
    .then((responseQuestion) => responseQuestion.json())
    .then((responseQuestion) => {
        if (responseQuestion.content == null) {
            sessionStorage.removeItem("count");
            sessionStorage.setItem("result", responseQuestion.result);
            window.location = `${window.url}/leaderboard/${id}`;
        }
        else {
            fetch(`${window.url}/api/play/${id}/answers`)
            .then((responseAnswers) => responseAnswers.json())
            .then((responseAnswers) => {
                fetch(`${window.url}/api/quizzes/${id}`)
                .then((responseSeconds) => responseSeconds.json())
                .then((responseSeconds) => {
                    fetch(`${window.url}/api/quizzes/${id}/questions/count`)
                    .then((responseCount) => responseCount.json())
                    .then((responseCount) => {
                        document.documentElement.style.setProperty('--quiz_seconds', responseSeconds.seconds_per_quiz + "s");
                        sessionStorage.setItem("header", responseSeconds.header);
                        sessionStorage.setItem("count", responseCount.count);
                        showQuestion(responseQuestion.content);
                        showAnswers(responseAnswers);
                        resetTimeBarProgress();
                        if (!sessionStorage.getItem("startProgressBar")) {
                            nextProgressBar();
                        }
                        else {
                            progressBar(1, responseCount.count);
                            sessionStorage.removeItem("startProgressBar");
                        }
                        nextButton.style.pointerEvents = "auto";
                    });
                });
            });
        }
    })
    .catch((error) => {
        document.getElementById("error").innerHTML = error;
    });
}

function init()
{
    const nextButton = document.getElementById("quiz_next_button");
    nextButton.addEventListener("click", () => play(window.quizId, nextButton));
    nextButton.style.pointerEvents = "none";
    sessionStorage.setItem("startProgressBar", 1);
    nextQuestion(window.quizId, nextButton);
}

document.addEventListener("DOMContentLoaded", init);
