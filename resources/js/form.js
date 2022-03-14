function buttonDisable(button)
{
    button.disabled = true;
    button.style.cursor = "auto";
    button.style.backgroundColor = getComputedStyle(button).getPropertyValue("--on_primary");
    button.form.submit();
}

function init()
{
    let button = document.getElementById("button_one_click");
    button.addEventListener("click", () => buttonDisable(button));
}

document.addEventListener("DOMContentLoaded", init);
