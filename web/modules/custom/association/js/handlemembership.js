window.onload = inzForm;

function inzForm() {

    displayFS1()

}

function hasChanged(oTemp) {

    switch (oTemp.id) {

        case "edit-suscribe-0":
        case "edit-suscribe-1":
            displayFS1()
            break;

    }
}

function displayFS1() {
    sDisplay = (document.getElementById("edit-suscribe-1").checked == 1) ? '' : 'none';
    document.getElementById("fs1").style.display = sDisplay;
}
