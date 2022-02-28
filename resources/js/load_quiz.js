
async function loadDataQuestion(id) {
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/question`);
    let data = await Response.json();
    document.getElementById("question").innerHTML = data.content;
}

async function loadDataAnswers(id) {
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/answers`);
    let data = await Response.json();
    document.getElementById("answers").innerHTML = "";
    dataLength = Object.keys(data).length;
    for (let i = 0; i < dataLength; i++) {
        let answer = document.createElement("div");
        answer.innerHTML = data[i].content;
        answer.classList.add("quiz_answer");
        answer.setAttribute("id", "answer" + (i + 1) + " " + data[i].id);
        answer.setAttribute("onclick", "answerOnClick(\"answer" + (i + 1) + " " + data[i].id + "\")");
        document.getElementById("answers").appendChild(answer);
    }
}

function init() {
    loadDataQuestion(count);
    loadDataAnswers(count);
}

document.addEventListener("DOMContentLoaded", init);
