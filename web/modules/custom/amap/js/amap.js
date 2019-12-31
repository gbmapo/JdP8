var iMaxD = drupalSettings.myConstants.nbmaxD; //Number of AMAPien per Distribution
var iMaxR = drupalSettings.myConstants.nbmaxR; //Number of Réserve per Distribution
var iMaxX = drupalSettings.myConstants.nbmaxX; //Number of Référent per Distribution
var bReferent = document.getElementById("breferentdistrib").value;

function hasChanged(oTemp) {

    sId = oTemp.id;
    iPos = sId.indexOf("][");
    sType = "[" + sId.substring(iPos + 2, iPos + 3).toLowerCase() + "]";

    sIdD = sId.replace(sType, "[d]");
    oD = document.getElementById(sIdD);
    sIdD2 = sId.replace(sType, "[d2]");
    oD2 = document.getElementById(sIdD2);
    iD = parseInt(oD2.value);

    sIdR = sId.replace(sType, "[r]");
    oR = document.getElementById(sIdR);
    sIdR2 = sId.replace(sType, "[r2]");
    oR2 = document.getElementById(sIdR2);
    iR = parseInt(oR2.value);

    sIdX = sId.replace(sType, "[x]");
    oX = document.getElementById(sIdX);
    sIdX2 = sId.replace(sType, "[x2]");
    oX2 = document.getElementById(sIdX2);
    iX = parseInt(oX2.value);

    if (oTemp.checked) {
        //L'utilisateur vient de cocher une boite
        switch (sType) {
            case "[d]":
                oD2.value = iD + 1;
                oR.disabled = true;
                oX.disabled = true;
                break;
            case "[r]":
                oD.disabled = true;
                oR2.value = iR + 1;
                oX.disabled = true;
                break;
            case "[x]":
                oD.disabled = true;
                oR.disabled = true;
                oX2.value = iX + 1;
                break;
            default:
                break;
        }
    } else {
        //L'utilisateur vient de décocher une boite
        switch (sType) {
            case "[d]":
                oD2.value = iD - 1;
                oR.disabled = (iR == iMaxR);
                if (bReferent == "Y") {
                    oX.disabled = (iX == iMaxX);
                }
                break;
            case "[r]":
                oD.disabled = (iD == iMaxD);
                oR2.value = iR - 1;
                if (bReferent == "Y") {
                    oX.disabled = (iX == iMaxX);
                }
                break;
            case "[x]":
                oD.disabled = (iD == iMaxD);
                oR.disabled = (iR == iMaxR);
                oX2.value = iX - 1;
                break;
            default:
                break;
        }
    }
}
