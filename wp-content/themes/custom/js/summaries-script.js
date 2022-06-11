function addSummary() {
    let indexCardForm = document.querySelector('#add_summary_form');
    let data = new FormData(indexCardForm);
    data.append('action', 'add_summary');
    data.append('nonce', summaries_args.nonce);

    fetch(summaries_args.post_url, {method: 'POST', credentials: 'same-origin', body: data})
        .then(response => {
            if (response.ok)
                return response.json();
            else {
                switch (response.status) {
                    case 415:
                        displaySnackbar('Dateityp nicht unterstützt', 'warning');
                        break;
                    case 409:
                        displaySnackbar('Die Datei existiert bereits', 'warning');
                        break;
                    default:
                        displaySnackbar('Die Zusammenfassung konnte nicht hochgeladen werden', 'error');
                        break;
                }
                throw new Error(response.statusText);
            }
        })
        .then(json => {
            displaySnackbar('Die Zusammenfassung wurde erfolgreich hochgeldaden', 'success');

            // Add uploaded file to summary list
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

            anchor.appendChild(spanFileName);
            anchor.appendChild(spanFileDate);
            li.appendChild(anchor);
            ul.appendChild(li);

            // Clear file input
            document.querySelector('#summary_to_upload').value = null;
        })
        .catch(error => {
            console.error(error)
        });
}
