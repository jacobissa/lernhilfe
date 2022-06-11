let snackbarHideTimeout;
let snackbarResetTimeout;

/**
 * Display snackbar.
 * @param message Snackbar message
 * @param variant Snackbar variant; one of "success", "error", "warning" or "info"
 */
const displaySnackbar = (message, variant) => {
    // build icon url depending on selected variant
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

    // cancel asynchronous snackbar tasks
    clearTimeout(snackbarHideTimeout);
    clearTimeout(snackbarResetTimeout);

    /**
     * Open snackbar.
     */
    const executeChange = () => {
        // clear all possible variants
        snackbar.classList.remove("success", "error", "warning", "info");

        // change icon and message
        iconElement.setAttribute("src", iconUrl);
        messageElement.innerText = message;

        // open snackbar
        snackbar.classList.add(variant, "snackbar-visible");

        // hide it after 5 seconds
        snackbarHideTimeout = setTimeout(() => snackbar.classList.remove("snackbar-visible"), 5000);
    }

    // check if snackbar is currently displayed
    if (snackbar.classList.contains("snackbar-visible")) {
        // hide it and wait for it to hide, then open it
        snackbar.classList.remove("snackbar-visible");

        snackbarResetTimeout = setTimeout(executeChange, 500);
    } else {
        // open it
        executeChange();
    }
}
