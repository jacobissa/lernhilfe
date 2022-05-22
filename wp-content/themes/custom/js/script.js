const toggleMenu = () => {
    const menu = document.querySelector("#navbar");
    const open = menu.classList.contains("open");
    menu.classList.remove(open ? "open" : "closed");
    menu.classList.add(open ? "closed" : "open");
}

const setChildrenDisabled = (selector, disabled) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea, ${selector} > button`)
        .forEach(element => disabled ?? true ? element.setAttribute("disabled", "") : element.removeAttribute("disabled"));
}

const clearInputs = (selector) => {
    document.querySelectorAll(`${selector} > input, ${selector} > select, ${selector} > textarea`)
        .forEach(element => element.value = "");
}
