function formDisable(button)
{
    let inputs = document.getElementsByTagName("input");
    for (let i = 1; i < inputs.length; i++) {
        inputs[i].disabled = true;
    }
    try {
        document.getElementById("remember_me_label").style.cursor = "auto";
        document.getElementById("remember_me").style.cursor = "auto";
    }
    catch (error) {}
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
    formButton.addEventListener("click", () => formDisable(formButton));
    document.getElementById("show_password").addEventListener("click", () => showPassword());
}

document.addEventListener("DOMContentLoaded", init);
