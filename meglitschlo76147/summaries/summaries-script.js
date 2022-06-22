/** Add change event listener to the file input to toggle the disabled property */
window.addEventListener('load', function () {
    let fileInput = document.querySelector('#summary_to_upload');
    fileInput?.addEventListener('change', toggleSubmitSummary);
});

/** disable or enable the add summary button depending on whether the input is empty nor not */
function toggleSubmitSummary(e) {
    let submitSummary = document.querySelector('#add_summary');
    if (e.target.value === null || e.target.value === '') {
        submitSummary.setAttribute('disabled', 'disabled');
    } else {
        submitSummary.removeAttribute('disabled');
    }
}


/** Adds a new Summary to the course via an asynchronous fetch request */
function addSummary() {
    // Create the request body data from the add summary form
    let indexCardForm = document.querySelector('#add_summary_form');
    let data = new FormData(indexCardForm);
    data.append('action', 'add_summary');
    data.append('nonce', summaries_args.nonce);

    // Fetch the post request and handle the response
    fetch(summaries_args.post_url, {method: 'POST', credentials: 'same-origin', body: data})
        .then(response => {
            // display a helpful response message with the snackbar
            if (response.ok) {
                snackbarAvailable && displaySnackbar(__('The summary was uploaded successfully', summaries_args.text_domain), 'success');
                return response.json();
            } else {
                switch (response.status) {
                    case 415:
                        snackbarAvailable && displaySnackbar(__('Unsupported filetype', summaries_args.text_domain), 'warning');
                        break;
                    case 409:
                        snackbarAvailable && displaySnackbar(__('The file already exists', summaries_args.text_domain), 'warning');
                        break;
                    case 401:
                        snackbarAvailable && displaySnackbar(__('Only logged in users are allowed to upload summaries', summaries_args.text_domain), 'error');
                        break;
                    default:
                        snackbarAvailable && displaySnackbar(__('The summary could not be uploaded', summaries_args.text_domain), 'error');
                        break;
                }
                throw new Error(response.statusText);
            }
        })
        .then(json => {
            // Add uploaded file to summary list without having to refresh the page
            let ul = document.querySelector('#summaries_list');

            let li = document.createElement('li');
            li.setAttribute('class', 'striped-list-item');

            let anchor = document.createElement('a');
            anchor.setAttribute('class', 'summaries-list-anchor');
            anchor.setAttribute('id', 'wp-block-file-pdf');
            anchor.setAttribute('target', '_blank');
            anchor.setAttribute('href', json.data.fullUrl);

            let spanFileName = document.createElement('span');
            spanFileName.setAttribute('class', 'list-file-name');
            spanFileName.appendChild(document.createTextNode(json.data.name));

            let spanFileDate = document.createElement('span');
            spanFileDate.appendChild(document.createTextNode(json.data.date));

            let spanFileAuthor = document.createElement('span');
            spanFileAuthor.appendChild(document.createTextNode(json.data.user));

            anchor.appendChild(spanFileName);
            anchor.appendChild(spanFileDate);
            anchor.appendChild(spanFileAuthor);
            li.appendChild(anchor);
            ul.appendChild(li);

            // Clear file input and disable submit button
            document.querySelector('#summary_to_upload').value = null;
            document.querySelector('#add_summary').setAttribute('disabled', 'disabled');
        })
        .catch(error => {
            console.error(error)
        });
}
