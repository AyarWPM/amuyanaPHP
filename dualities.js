function editDuality(divId, container2Id, currentTable){
  var c = document.getElementById("editModeChBx");
  if(!c.checked){
    return;
  }
  var id = divId.split("dual").pop();
  $("#dualityEditor").load("dualityEditor.php", {
    id_fcc: id,
    id_container_2: container2Id,
    table: currentTable
  });
  $("#dualityEditor").show(); 
}

function closeDualityEditor(){
  $("#dualityEditor").hide();
}

function removeDuality(currentTable, idFcc, idContainer2){
  var t = currentTable;
  var f = idFcc;
  var c2 = idContainer2;
  $("#dualityEditor").load("removeDuality.php");
}

function refresh(location){
  location.reload(true); 
}
function confirmDelete(){
  $("#deleteDualityBtn").hide();
  $("#confirmDeleteDuality").show();
}
function cancelDelete(){
  $("#confirmDeleteDuality").hide();
  $("#deleteDualityBtn").show();
}

function toggleEditMode(showMessage){
  
  var n = document.getElementById("newTableBtn");
  var checkBox = document.getElementById("editModeChBx");
  if(checkBox.checked){
    if(showMessage == 1){
      alert("With great power comes great responsibility.");
    }
    isEditMode = true;
    $("#deleteTableBtn").fadeIn();
    $("#newTableBtn").fadeIn();
    $("#hint").fadeIn();
  } else {
    isEditMode = false;
    $("#deleteTableBtn").fadeOut();
    $("#newTableBtn").fadeOut();
  }
  
  
}