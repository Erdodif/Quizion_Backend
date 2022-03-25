
function loadQuizHeader(id)
{
    return fetch(`${window.url}/api/quizzes/${id}`)
    .then(function (response) {
        return response.json();
    });
}

async function loadLeaderboard(id)
{
    let response = await fetch(`${window.url}/api/leaderboard/${id}`);
    let data = await response.json();
    document.getElementById("leaderboard").innerHTML = "";
    let rows = Object.keys(data).length;
    let table = document.createElement("table");
    createTableTh(table);
    for (let i = 0; i < rows; i++) {
        let tr = document.createElement("tr");
        tr.appendChild(addColumn("td", data[i].rank));
        tr.appendChild(addColumn("td", data[i].points));
        tr.appendChild(addColumn("td", data[i].name));
        if (window.username == data[i].name) {
            tr.classList.add("leaderboard_my_name");
        }
        table.appendChild(tr);
    }
    document.getElementById("leaderboard").appendChild(table);
}

function addColumn(htmlThTr, item)
{
    let htmlElement = document.createElement(htmlThTr);
    htmlElement.innerHTML = item;
    return htmlElement;
}

function createTableTh(table)
{
    let trTh = document.createElement("tr");
    trTh.appendChild(addColumn("th", "Rank"));
    trTh.appendChild(addColumn("th", "Points"));
    trTh.appendChild(addColumn("th", "Username"));
    table.appendChild(trTh);
}

function init()
{
    if (sessionStorage.getItem("header")) {
        document.getElementById("title").innerHTML = sessionStorage.getItem("header");
        sessionStorage.removeItem("header");
    }
    else {
        loadQuizHeader(window.quizId).then(function (response) {
            (response) => response.json();
            document.getElementById("title").innerHTML = response.header;
        });
    }
    if (sessionStorage.getItem("result")) {
        document.getElementById("result").innerHTML = sessionStorage.getItem("result");
        sessionStorage.removeItem("result");
    }
    loadLeaderboard(window.quizId);
}

document.addEventListener("DOMContentLoaded", init);
