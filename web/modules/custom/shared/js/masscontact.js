function hasChanged(oTemp) {
    switch (oTemp.id) {
    case "edit-views-send-from-name-bis":
        switch (oTemp.value) {
        case "1":
            document.getElementById("edit-views-send-from-name").value = oTemp[1].text;
            document.getElementById("edit-views-send-from-mail").value = document.getElementById("edit-views-send-from-mail-currentuser").value;
            document.getElementById("edit-views-send-subject").value = "[Le Jardin de Poissy] ";
            break;
        case "2":
            document.getElementById("edit-views-send-from-name").value = "Le Jardin de Poissy";
            document.getElementById("edit-views-send-from-mail").value = "contact@lejardindepoissy.org";
            document.getElementById("edit-views-send-subject").value = "[Le Jardin de Poissy] ";
            break;
        case "3":
            document.getElementById("edit-views-send-from-name").value = "L\'AMAP du Jardin de Poissy";
            document.getElementById("edit-views-send-from-mail").value = "amap@lejardindepoissy.org";
            document.getElementById("edit-views-send-subject").value = "[L\'AMAP du Jardin de Poissy] ";
            break;
        case "4":
            document.getElementById("edit-views-send-from-name").value = "Le Grenier à SEL";
            document.getElementById("edit-views-send-from-mail").value = "sel@lejardindepoissy.org";
            document.getElementById("edit-views-send-subject").value = "[Le Grenier à SEL] ";
            break;
        }
        break;
    }
}


