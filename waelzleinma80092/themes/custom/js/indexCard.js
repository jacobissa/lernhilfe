/**
 * Flips the index card to display the content on the back.
 */
const flipIndexCard = () => {
    document.querySelector('.flip-card-inner').classList.add('flip-card-flipped');
    document.querySelector('.displayed-index-card.flip-card-front').disabled = true;
    document.querySelector('.displayed-index-card.flip-card-back').disabled = false;
}

/**
 * Asynchronously calls the backend to save a new index card.
 */
const addIndexCard = courseId => {
    let formSelector = "#index-card-form";
    const indexCardForm = document.querySelector(formSelector);

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
        snackbarAvailable && displaySnackbar(__("Please specify both question and answer", indexcards_wordpress_vars.domain), "warning");
        return;
    }

    // make sure course id has been passed and append it
    if (courseId === null || courseId === undefined) {
        console.error("course id for index card not specified");
        return;
    }

    // prevent form edit
    setChildrenDisabledProperty(formSelector);

    // disable add button
    document.querySelector("#add-index-card-button").disabled = true;

    data.append("course_id", courseId);

    // append wordpress ajax properties
    data.append("action", indexcards_wordpress_vars.add_action);
    data.append("nonce", indexcards_wordpress_vars.nonce);

    fetch(indexcards_wordpress_vars.post_url, {method: "POST", credentials: "same-origin", body: data})
        .then(response => {
            if (response.ok) {
                // reset form
                clearInputs(formSelector);

                snackbarAvailable && displaySnackbar(__("Index card saved", indexcards_wordpress_vars.domain), "success");
            } else {
                // error out on HTTP error
                throw new Error(response.statusText);
            }
        })
        .catch(() => {
            snackbarAvailable && displaySnackbar(__("Could not save index card", indexcards_wordpress_vars.domain), "error");
        })
        .finally(() => {
            // allow edit
            setChildrenDisabledProperty(formSelector, false)
            // enable add button
            document.querySelector("#add-index-card-button").disabled = false;
            }
        );
};

/**
 * Asynchronously calls the backend to delete an index card.
 */
const deleteIndexCard = id => {
    // disable delete button
    document.querySelector("#delete-index-card-button").disabled = true;

    const data = new FormData();
    data.append("index_card_id", id)

    // append wordpress ajax properties
    data.append("action", indexcards_wordpress_vars.delete_action);
    data.append("nonce", indexcards_wordpress_vars.nonce);

    fetch(indexcards_wordpress_vars.post_url, {method: "POST", credentials: "same-origin", body: data})
        .then(response => {
            if (response.ok)
                // reload page
                window.location.reload();
            else
                // error out on HTTP error
                throw new Error(response.statusText);
        })
        .catch(() => {
            snackbarAvailable && displaySnackbar(__("Could not delete index card", indexcards_wordpress_vars.domain), "error");
        });
};