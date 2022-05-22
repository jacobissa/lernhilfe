const toggleMenu = () => {
    const menu = document.querySelector("#navbar");
    const open = menu.classList.contains("open");
    menu.classList.remove(open ? "open" : "closed");
    menu.classList.add(open ? "closed" : "open");
}