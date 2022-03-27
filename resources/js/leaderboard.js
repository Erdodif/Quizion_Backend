
function loadTitle(id) {
    if (sessionStorage.getItem("header")) {
        document.getElementById("title").innerHTML = sessionStorage.getItem("header");
        sessionStorage.removeItem("header");
    }
    else {
        fetch(`${window.url}/api/quizzes/${id}`)
        .then((response) => response.json())
        .then((response) => {
            document.getElementById("title").innerHTML = response.header;
        });
    }
}

function loadResult() {
    if (sessionStorage.getItem("result")) {
        document.getElementById("result").innerHTML = sessionStorage.getItem("result");
        sessionStorage.removeItem("result");
    }
}

function createTableTh(table)
{
    let trTh = document.createElement("tr");
    trTh.appendChild(addColumn("th", "Rank"));
    trTh.appendChild(addColumn("th", "Points"));
    trTh.appendChild(addColumn("th", "Username"));
    table.appendChild(trTh);
}

function addColumn(htmlThTr, item)
{
    let htmlElement = document.createElement(htmlThTr);
    htmlElement.innerHTML = item;
    return htmlElement;
}

async function loadLeaderboard(id)
{
    let response = await (await fetch(`${window.url}/api/leaderboard/${id}`)).json();
    document.getElementById("leaderboard").innerHTML = "";
    let rows = Object.keys(response).length;
    let table = document.createElement("table");
    createTableTh(table);
    for (let i = 0; i < rows; i++) {
        let tr = document.createElement("tr");
        tr.appendChild(addColumn("td", response[i].rank));
        tr.appendChild(addColumn("td", response[i].points));
        tr.appendChild(addColumn("td", response[i].name));
        if (window.username == response[i].name) {
            tr.classList.add("leaderboard_my_name");
        }
        table.appendChild(tr);
    }
    document.getElementById("leaderboard").appendChild(table);
}

function init()
{
    loadTitle(window.quizId);
    loadResult();
    loadLeaderboard(window.quizId);
}

document.addEventListener("DOMContentLoaded", init);
