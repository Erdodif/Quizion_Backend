function buttonDisable(button)
{
    button.disabled = true;
    button.style.cursor = "auto";
    button.style.backgroundColor = getComputedStyle(button).getPropertyValue("--on_primary");
    button.form.submit();
}

function showPassword() {
    try {
        let password = document.getElementsByClassName("password");
        if (password[0].type == "password") {
            password[0].type = "text";
            password[1].type = "text";
        }
        else {
            password[0].type = "password";
            password[1].type = "password";
        }
    }
    catch (error) {}
}

function init()
{
    let formButton = document.getElementById("button_one_click");
    formButton.addEventListener("click", () => buttonDisable(formButton));
    try {
        document.getElementById("show_password").addEventListener("click", () => showPassword());
    }
    catch (error) {}
}

document.addEventListener("DOMContentLoaded", init);
