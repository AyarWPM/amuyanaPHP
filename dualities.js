function editDuality(divId, container2Id, currentTable){
  var id = divId.split("dual").pop();
  $("#dualityEditor").load("dualityEditor.php", {
    id_fcc: id,
    id_container_2: container2Id,
    table: currentTable
  });
  $("#dualityEditor").show(); 
  // $("#dualityEditor" ).resizable(); 
}

function closeDualityEditor(){
  $("#dualityEditor").hide();
}


function removeDuality(currentTable, idFcc, idContainer2){
  var t = currentTable;
  var f = idFcc;
  var c2 = idContainer2;
  $("#dualityEditor").load("removeDuality.php");
  // $('#dualityEditor').show(); 
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
// function saveDualityEditor(id_fcc, name, description, element, antiElement, positiveLabel, positiveDescription, negativeLabel, negativeDescription, symmetricLabel, symmetricDescription){
//   $("#dualityEditor").load("dualityEditor.php", {
//     id_fcc: id
//   });
// }