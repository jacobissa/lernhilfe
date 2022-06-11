const {__} = wp.i18n;

const toggleMenu = () => {
    const menu = document.querySelector("#navbar");
    const previouslyOpen = menu.classList.contains("open");
    menu.classList.remove(previouslyOpen ? "open" : "closed");
    menu.classList.add(previouslyOpen ? "closed" : "open");
    const menuItems = document.querySelectorAll("#navbar a");
    menuItems.forEach(item => previouslyOpen ? item.tabIndex = -1 : item.removeAttribute("tabindex"));
}

const setChildrenDisabled = (selector, disabled) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea, ${selector} > button`)
        .forEach(element => disabled ?? true ? element.setAttribute("disabled", "") : element.removeAttribute("disabled"));
}

const clearInputs = (selector) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea`)
        .forEach(element => element.value = "");
}

const snackbarAvailable = typeof (displaySnackbar) === typeof (Function);
