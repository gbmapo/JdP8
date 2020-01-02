var iCurrentUser = "";
window.onload = inzForm;

function inzForm() {
	iCurrentUser=document.getElementById("current-user-id").value;
}

function hasChanged(oTemp) {

	switch(oTemp.id){

		case "edit-from-seliste-id-wrapper":
		var oTemp2 = document.getElementById("edit-from-seliste-id");
		iTemp=oTemp2.options[oTemp2.selectedIndex].value;
		if (iTemp!=iCurrentUser){setSelect(document.getElementById("edit-to-seliste-id"), iCurrentUser);}
		break;

		case "edit-to-seliste-id-wrapper":
		var oTemp2 = document.getElementById("edit-to-seliste-id");
		iTemp=oTemp2.options[oTemp2.selectedIndex].value;
		if (iTemp!=iCurrentUser){setSelect(document.getElementById("edit-from-seliste-id"), iCurrentUser);}
		break;

	}
}

function setSelect(oSelect, iValue){
	for(var i = 0; i < oSelect.length; i++) {
		if(oSelect.options[i].value == iValue){
			oSelect.options[i].selected = true;
			break;
		}
	}
}
