let snackbarHideTimeout;
let snackbarResetTimeout;

const displaySnackbar = (message, variant) => {
    let iconUrl = snackbar_wordpress_vars.template_dir + "/svg/";
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
    let iconElement = document.querySelector("#snackbar-icon");
    let messageElement = document.querySelector("#snackbar-message");

    clearTimeout(snackbarHideTimeout);
    clearTimeout(snackbarResetTimeout);

    const executeChange = () => {
        snackbar.classList.remove("success", "error", "warning", "info");
        snackbar.classList.add(variant, "snackbar-visible");
        iconElement.setAttribute("src", iconUrl);
        messageElement.innerText = message;

        snackbarHideTimeout = setTimeout(() => snackbar.classList.remove("snackbar-visible"), 5000);
    }

    if (snackbar.classList.contains("snackbar-visible")) {
        snackbar.classList.remove("snackbar-visible");

        snackbarResetTimeout = setTimeout(executeChange, 500);
    } else {
        executeChange();
    }
}
