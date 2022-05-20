function showAnswer(answerId) {
    if (answerId) {
        let element = document.getElementById(answerId);
        if (element) {
            element.style.display === "none" || element.style.display === "" ?
                element.style.display = "block" : element.style.display = "none";
        }
    }
}