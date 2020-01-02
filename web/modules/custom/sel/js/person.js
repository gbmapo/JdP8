window.onload = inzForm;

function inzForm() {

    displayBalance()

}

function hasChanged(oTemp) {

    switch (oTemp.id) {

        case "edit-field-sel-isseliste-wrapper":
            displayBalance()
            break;

    }
}

function displayBalance() {
    sDisplay = (document.getElementById("edit-field-sel-isseliste-value").checked == 1) ? '' : 'none';
    document.getElementById("edit-field-sel-balance-wrapper").style.display = sDisplay;
}
