let snackbarHideTimeout;
let snackbarResetTimeout;

const displaySnackbar = (message, variant) => {
    let iconUrl = snackbar_vars.template_dir + "/svg/";
    switch (variant) {
        case "success":
            iconUrl += "success.svg";
            break;
        case "error":
            iconUrl += "error.svg";
            break;
        case "warning":
            iconUrl += "warning.svg";
            break;
        case "info":
            iconUrl += "info.svg";
            break;
        default:
            throw new Error(`Unknown snackbar variant ${variant}`)
    }

    let snackbar = document.querySelector("#snackbar");
    let iconElement = document.querySelector("#snackbar_icon");
    let messageElement = document.querySelector("#snackbar_message");

    clearTimeout(snackbarHideTimeout);
    clearTimeout(snackbarResetTimeout);

    const executeChange = () => {
        snackbar.classList.remove("success", "error", "warning", "info");
        snackbar.classList.add(variant, "snackbar_visible");
        iconElement.setAttribute("src", iconUrl);
        messageElement.innerText = message;

        snackbarHideTimeout = setTimeout(() => snackbar.classList.remove("snackbar_visible"), 5000);
    }

    if (snackbar.classList.contains("snackbar_visible")) {
        snackbar.classList.remove("snackbar_visible");

        snackbarResetTimeout = setTimeout(executeChange, 500);
    } else {
        executeChange();
    }
}
