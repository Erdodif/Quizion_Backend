
async function loadDataQuestion(id) {
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/question`);
    let data = await Response.json();
    document.getElementById("question").innerHTML = data.content;
}

async function loadDataAnswers(id) {
    let Response = await fetch(`http://127.0.0.1:8000/api/play/${id}/answers`);
    let data = await Response.json();
    document.getElementById("answers").innerHTML = "";
    let dataLength = Object.keys(data).length;
    for (let i = 0; i < dataLength; i++) {
        let answer = document.createElement("div");
        answer.innerHTML = data[i].content;
        answer.classList.add("quiz_answer");
        answer.setAttribute("id", "answer" + (i + 1));
        answer.setAttribute("onclick", "answerOnClick(" + (i + 1) + ")");
        document.getElementById("answers").appendChild(answer);
    }
}
/*
async function newGame(id) {
    let answerId = document.getElementById("pickAnswer").value;
    const data = { "chosen": [answerId] };
    console.log(data["chosen"]);
    await fetch(`http://127.0.0.1:8000/api/play/${id}/choose`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
*/
function init() {
    loadDataQuestion(count);
    loadDataAnswers(count);
}

document.addEventListener("DOMContentLoaded", init);
