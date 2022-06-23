const {__} = wp.i18n;

/**
 * Opens or closes sidebar.
 */
const toggleMenu = () => {
    const menu = document.querySelector("#navbar");
    const previouslyOpen = menu.classList.contains("open");
    menu.classList.remove(previouslyOpen ? "open" : "closed");
    menu.classList.add(previouslyOpen ? "closed" : "open");
    const menuItems = document.querySelectorAll("#navbar a");
    menuItems.forEach(item => previouslyOpen ? item.tabIndex = -1 : item.removeAttribute("tabindex"));
}

/**
 * Sets "disabled" property on direct children of an HTML element.
 * @param selector Query selector for parent
 * @param disabled New disabled property
 */
const setChildrenDisabledProperty = (selector, disabled) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea, ${selector} > button`)
        .forEach(element => element.disabled = disabled ?? true);
}

/**
 * Clears value from input fields which are direct children of an HTML element.
 * @param selector Query selector for parent
 */
const clearInputs = (selector) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea`)
        .forEach(element => element.value = "");
}

const snackbarAvailable = typeof (displaySnackbar) === typeof (Function);
