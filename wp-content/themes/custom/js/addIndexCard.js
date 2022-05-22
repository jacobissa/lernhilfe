function addIndexCard() {
    let formSelector = "#index_card_form";
    const index_card_form = document.querySelector(formSelector);

    const data = new FormData(index_card_form);

    const indexCardQuestion = document.querySelector("#index_card_question_input");
    const indexCardAnswer = document.querySelector("#index_card_answer_input");
    indexCardQuestion.style.borderColor = "dimgray";
    indexCardAnswer.style.borderColor = "dimgray";
    if (!data.get("question") || !data.get("answer")) {
        if (!data.get("question")) {
            indexCardQuestion.style.borderColor = "red";
        }
        if (!data.get("answer")) {
            indexCardAnswer.style.borderColor = "red";
        }
        return;
    }

    data.append("action", "add_index_card");
    data.append("nonce", add_index_card_vars.nonce);

    setChildrenDisabled(formSelector);

    fetch(add_index_card_vars.post_url, {method: "POST", credentials: "same-origin", body: data})
        .then(response => {
            if (response.ok)
                return response.json();
            else
                throw new Error(response.statusText);
        })
        .then(() => {
            clearInputs(formSelector);
            displaySnackbar("Die Karteikarte wurde erfolgreich gespeichert", "success");
        })
        .catch(() => {
            displaySnackbar("Die Karteikarte konnte nicht gespeichert werden", "error");
        })
        .finally(() => setChildrenDisabled(formSelector, false));
}