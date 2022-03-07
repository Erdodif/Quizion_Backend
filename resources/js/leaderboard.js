
async function loadDataLeaderboard(id)
{
    let Response = await fetch(`http://127.0.0.1:8000/api/leaderboard/${id}`);
    let data = await Response.json();
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
    document.getElementById("title").innerHTML = "This is the " + window.quizId + ". quiz's leaderboard.";
    if (sessionStorage.getItem("result")) {
        document.getElementById("result").innerHTML = sessionStorage.getItem("result");
        sessionStorage.removeItem("result");
    }
    loadDataLeaderboard(window.quizId);
}

document.addEventListener("DOMContentLoaded", init);
