function hasChanged(oTemp) {

  switch (oTemp.id) {

    case "edit-lastname1":
    case "edit-firstname1":
    case "edit-lastname2":
    case "edit-firstname2":
    case "edit-city":
      oTemp.value = capitalizeWords(oTemp.value);
      break;
    case "edit-email1":
    case "edit-email2":
    case "edit-addresssupplement":
    case "edit-street":
      oTemp.value = oTemp.value.toLowerCase();
      break;
    case "edit-cellphone1":
    case "edit-cellphone2":
    case "edit-telephone":
      oTemp.value = oTemp.value.replace(/[\D]/g, '');
      break;
  }
}

function capitalizeWords(str) {
  var words = str.toLowerCase().split(" ");
  for (var i = 0; i < words.length; i++) {
    var j = words[i].charAt(0).toUpperCase();
    words[i] = j + words[i].substr(1);
  }
  return words.join(" ");
}
