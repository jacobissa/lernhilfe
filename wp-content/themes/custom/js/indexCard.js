const flipIndexCard = () => {
    document.querySelector('.flip-card-inner').classList.add('flip-card-flipped');
    document.querySelector('.displayed-index-card.flip-card-front').disabled = true;
    document.querySelector('.displayed-index-card.flip-card-back').disabled = false;
}

const addIndexCard = courseId => {
    let formSelector = "#index-card-form";
    const indexCardForm = document.querySelector(formSelector);

    // load data
    const data = new FormData(indexCardForm);

    // validate and display result
    const indexCardQuestion = document.querySelector("#index-card-question-input");
    const indexCardAnswer = document.querySelector("#index-card-answer-input");
    indexCardQuestion.style.borderColor = "dimgray";
    indexCardAnswer.style.borderColor = "dimgray";
    if (!data.get("question") || !data.get("answer")) {
        if (!data.get("question")) {
            indexCardQuestion.style.borderColor = "red";
        }
        if (!data.get("answer")) {
            indexCardAnswer.style.borderColor = "red";
        }
        displaySnackbar("Bitte gib Frage und Antwort an", "warning");
        return;
    }

    // make sure course id has been passed and append it
    if (courseId === null || courseId === undefined) {
        console.error("course id for index card not specified");
        return;
    }
    data.append("course_id", courseId);

    // append wordpress ajax properties
    data.append("action", index_card_vars.add_action);
    data.append("nonce", index_card_vars.nonce);

    // prevent edit
    setChildrenDisabled(formSelector);

    fetch(index_card_vars.post_url, {method: "POST", credentials: "same-origin", body: data})
        .then(response => {
            // load response
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
        .finally(() =>
            // allow edit
            setChildrenDisabled(formSelector, false));
};

const deleteIndexCard = id => {
    const data = new FormData();
    data.append("index_card_id", id)
    data.append("action", index_card_vars.delete_action);
    data.append("nonce", index_card_vars.nonce);

    fetch(index_card_vars.post_url, {method: "POST", credentials: "same-origin", body: data})
        .then(response => {
            if (response.ok)
                return response.json();
            else
                throw new Error(response.statusText);
        })
        .then(() => {
            window.location.reload();
        })
        .catch(() => {
            displaySnackbar("Die Karteikarte konnte nicht gelöscht werden", "error");
        });
};