
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

function setQuizSecondsToAnimationTimeBar(seconds) {
    seconds++;
    document.documentElement.style.setProperty('--quiz_seconds', seconds + "s");
}

function setSessionStorageHeader(header) {
    sessionStorage.setItem("header", header);
}

function setSessionStorageCount(count) {
    sessionStorage.setItem("count", count);
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

function resetTimeBarProgress(animation) {
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

function chooseAnswer(id, nextButton, animationTimeBar, timedOut)
{
    nextButton.classList.toggle("disable");
    let array = idToChosen();
    if (array || timedOut) {
        if (timedOut) {
            array = [0];
        }
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
            if (response.ok || response.status == 408) {
                nextQuestion(id, nextButton, animationTimeBar);
            }
        })
        .catch((error) => {
            document.getElementById("error").innerHTML = error;
        });
    }
    else {
        nextButton.classList.toggle("disable");
    }
}

function nextQuestion(id, nextButton, animationTimeBar) {
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
                if (!sessionStorage.getItem("start")) {
                    showQuestion(responseQuestion.content);
                    showAnswers(responseAnswers);
                    nextProgressBar();
                    resetTimeBarProgress(animationTimeBar);
                    nextButton.classList.toggle("disable");
                }
                else {
                    fetch(`${window.url}/api/quizzes/${id}`)
                    .then((responseQuiz) => responseQuiz.json())
                    .then((responseQuiz) => {
                        fetch(`${window.url}/api/quizzes/${id}/questions/count`)
                        .then((responseCount) => responseCount.json())
                        .then((responseCount) => {
                            setQuizSecondsToAnimationTimeBar(responseQuiz.seconds_per_quiz);
                            progressBar(1, responseCount.count);
                            setSessionStorageHeader(responseQuiz.header);
                            setSessionStorageCount(responseCount.count);
                            showQuestion(responseQuestion.content);
                            showAnswers(responseAnswers);
                            sessionStorage.removeItem("start");
                            nextButton.classList.toggle("disable");
                        });
                    });
                }
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
    const animationTimeBar = document.getElementById("time_bar_progress");
    nextButton.addEventListener("click", () => chooseAnswer(window.quizId, nextButton, animationTimeBar, false));
    animationTimeBar.addEventListener("animationend", () => chooseAnswer(window.quizId, nextButton, animationTimeBar, true));
    nextButton.classList.toggle("disable");
    sessionStorage.setItem("start", 1);
    nextQuestion(window.quizId, nextButton, animationTimeBar);
}

document.addEventListener("DOMContentLoaded", init);
