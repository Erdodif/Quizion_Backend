function buttonDisable(button)
{
    button.disabled = true;
    button.style.cursor = "auto";
    button.style.backgroundColor = getComputedStyle(button).getPropertyValue("--on-primary");
    button.form.submit();
}

function showPassword() {
    let password = document.getElementById("password");
    if (password.type == "password") {
        password.type = "text";
    }
    else {
        password.type = "password";
    }
}

function init()
{
    try {
        let formButton = document.getElementById("button-one-click");
        formButton.addEventListener("click", () => buttonDisable(formButton));
        document.getElementById("show-password").addEventListener("click", () => showPassword());
    }
    catch (error) {}
}

document.addEventListener("DOMContentLoaded", init);
