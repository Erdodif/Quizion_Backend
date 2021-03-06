
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

function setQuizSecondsToAnimationTimeBar(seconds)
{
    document.documentElement.style.setProperty('--quiz_seconds', seconds + "s");
}

function stopOrContinueTimeBarProgress(animation)
{
    animation.classList.toggle("animation-pause");
}

function resetTimeBarProgress(animation)
{
    animation.style.animation = "none";
    animation.offsetHeight;
    animation.style.animation = null;
}

function setSessionStorageHeader(header)
{
    sessionStorage.setItem("header", header);
}

function setSessionStorageCount(count)
{
    sessionStorage.setItem("count", count);
}

function progressBar(currentQuestion, numberOfQuestions)
{
    document.getElementById("progress-bar-color").style.width = (currentQuestion / numberOfQuestions * 100) + "%";
    document.getElementById("progress-bar-text").innerHTML = currentQuestion + "/" + numberOfQuestions;
}

function nextProgressBar()
{
    let currentQuestion = document.getElementById("progress-bar-text").innerHTML;
    currentQuestion = currentQuestion.split("/")[0];
    currentQuestion++;
    progressBar(currentQuestion, sessionStorage.getItem("count"));
}

function outOfTime(message)
{
    document.getElementById("out-of-time").innerHTML = message;
    if (message != "") {
        let answers = document.getElementsByClassName("quiz-answer");
        for (let i = 0; i < answers.length; i++) {
            answers[i].classList.toggle("disable");
        }
    }
}

function showQuestion(question)
{
    document.getElementById("question").innerHTML = question;
}

function showAnswers(answers)
{
    dataLength = Object.keys(answers).length;
    document.getElementById("answers").innerHTML = "";
    for (let i = 0; i < dataLength; i++) {
        let answer = document.createElement("div");
        answer.innerHTML = answers[i].content;
        answer.classList.add("quiz-answer");
        answer.setAttribute("id", "answer" + (i + 1) + " " + answers[i].id);
        answer.addEventListener("click", () => answerOnClick("answer" + (i + 1) + " " + answers[i].id));
        document.getElementById("answers").appendChild(answer);
    }
}

function responseAnswer(response, animationTimeBar)
{
    stopOrContinueTimeBarProgress(animationTimeBar);
    let answers = document.getElementsByClassName("quiz-answer");
    for (let i = 0; i < response.length; i++) {
        answers[i].classList.toggle("disable");
        let responseIsRight = response[i].is_right;
        let responseId = response[i].id;
        let answerId = answers[i].id.split(" ")[1];
        for (let j = 1; j < response.length + 1; j++) {
            if (responseId == answerId && responseIsRight == 0) {
                try {
                    document.getElementById("answer" + j + " " + answerId).classList.toggle("selected");
                    document.getElementById("answer" + j + " " + answerId).classList.toggle("is-wrong");
                }
                catch (error) {}
            }
            else if (responseId == answerId && responseIsRight == 1) {
                try {
                    document.getElementById("answer" + j + " " + answerId).classList.toggle("selected");
                    document.getElementById("answer" + j + " " + answerId).classList.toggle("is-right");
                }
                catch (error) {}
            }
        }
    }
}

function sendAnswer(id, sendAnswerButton, nextQuestionButton, animationTimeBar, timedOut)
{
    let array = idToChosen();
    if (array || timedOut) {
        sendAnswerButton.classList.add("disable");
        if (timedOut) {
            array = [0];
            outOfTime("Out of time!");
        }
        const data = { chosen: array };
        fetch(`${window.url}/api/play/${id}/choose`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            body: JSON.stringify(data)
        })
        .then((response) => response.json())
        .then((response) => {
            responseAnswer(response, animationTimeBar);
            sendAnswerButton.classList.add("display-none");
            nextQuestionButton.classList.remove("display-none");
            nextQuestionButton.classList.remove("disable");
        })
        .catch((error) => {
            document.getElementById("error").innerHTML = error;
        });
    }
}

function nextQuestion(id, sendAnswerButton, nextQuestionButton, animationTimeBar)
{
    if (!sessionStorage.getItem("start")) {
        nextQuestionButton.classList.add("disable");
    }
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
                    outOfTime("");
                    showQuestion(responseQuestion.content);
                    showAnswers(responseAnswers);
                    nextProgressBar();
                    stopOrContinueTimeBarProgress(animationTimeBar);
                    resetTimeBarProgress(animationTimeBar);
                    nextQuestionButton.classList.add("display-none");
                    sendAnswerButton.classList.remove("display-none");
                    sendAnswerButton.classList.remove("disable");
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
    const sendAnswerButton = document.getElementById("send-answer-button");
    const nextQuestionButton = document.getElementById("next-question-button");
    const animationTimeBar = document.getElementById("time-bar-progress");

    sendAnswerButton.addEventListener("click", () => sendAnswer(window.quizId, sendAnswerButton, nextQuestionButton, animationTimeBar, false));
    nextQuestionButton.addEventListener("click", () => nextQuestion(window.quizId, sendAnswerButton, nextQuestionButton, animationTimeBar));
    animationTimeBar.addEventListener("animationend", () => sendAnswer(window.quizId, sendAnswerButton, nextQuestionButton, animationTimeBar, true));

    nextQuestionButton.classList.add("display-none");
    nextQuestionButton.classList.add("disable");
    sessionStorage.setItem("start", 1);
    nextQuestion(window.quizId, sendAnswerButton, animationTimeBar);
}

document.addEventListener("DOMContentLoaded", init);
