var listDynamisms;
var listInclusions;

function setLists(){
  var d = document.getElementById('dynamismsArrayDiv').innerHTML;
  if(d){
    listDynamisms = JSON.parse(d);
  }
  var i = document.getElementById('inclusionsArrayDiv').innerHTML;
  if(i){
    listInclusions = JSON.parse(i);
  }
}

function mouseOverDynamism(hoverId){
  mouseLeaveDynamism();
  var id = hoverId.split("dyn").pop();
  // check if id is general
  listInclusions.forEach((inclusion, i) => {
  if(inclusion.id_general==id){
    var div1 = document.getElementById(hoverId);
    // if(hoverId!=selectedDynDivId){
    // }
    div1.setAttribute("style","background-color: rgba(255, 255, 155); border-radius:10px;");
    listDynamisms.forEach((item, i) => {
      var particular = item.split("dyn").pop();
      if(particular==inclusion.id_particular){
        // look which dyn is the particular
        listDynamisms.forEach((d, i) => {
          var dyn = d.split("dyn").pop();
          if(dyn == particular){
            var div2 = document.getElementById(d);
            div2.setAttribute("style","background-color: rgba(255, 255, 155);border-radius:10px;");
          }
        });
      }
    });
  }
  });
  // check if id is particular
  listInclusions.forEach((inclusion, i) => {
  if(inclusion.id_particular==id){
    var div1 = document.getElementById(hoverId);
    // if(hoverId!=selectedDynDivId){
    // }
    div1.setAttribute("style","background-color: rgba(255, 255, 155); border-radius:10px;");
    listDynamisms.forEach((item, i) => {
      var general = item.split("dyn").pop();
      if(general==inclusion.id_general){
        // look which dyn is the particular
        listDynamisms.forEach((d, i) => {
          var dyn = d.split("dyn").pop();
          if(dyn == general){
            var div2 = document.getElementById(d);
            div2.setAttribute("style","background-color: rgba(255, 255, 155);border-radius:10px;");
          }
        });
      }
    });
  }
  });
}

function mouseLeaveDynamism(){
    listDynamisms.forEach((id, i) => {
      var div = document.getElementById(id);
      div.setAttribute("style","background-color:transparent");
      // if(id!=selectedDynDivId){
      // }
    });
}

function zoomIn(){
  var transform = $("#tree").css("transform");
  var scale = "";
  for (let index = 7; index <= 9; index++) {
    scale += transform[index];
  }
  var parsedScale = parseFloat(scale);
  var newScale = parsedScale + 0.1;
  $("#tree").css("transform", "scale("+newScale+")");
}

function zoomOut(){
  var transform = $("#tree").css("transform");
  var scale = "";
  for (let index = 7; index <= 9; index++) {
    scale += transform[index];
  }
  var parsedScale = parseFloat(scale);
  var newScale = parsedScale - 0.1;
  $("#tree").css("transform", "scale("+newScale+")");
}

