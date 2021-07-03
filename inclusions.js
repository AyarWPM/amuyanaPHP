var listDynamisms;
var listGenerals;
var listParticulars;
var listInclusions;
var listContainer0s;
var listContainer1s;
var listContainer2s;
var isSelectedDyn = false;
var selectedDynDiv;
var selectedDynDivId;
var isEditMode=false;

function clickDynamism(todId, dynDivId){
  if(!document.getElementById("editModeChBx").checked){
    return;
  }
  var dynDiv = document.getElementById(dynDivId);
  dynDiv.setAttribute("style","background-color: rgba(0, 255, 155); border-radius:10px;");
  if(window.isSelectedDyn){
    // check which one is inside which one
    // H1: selected is parent
    var h1a = false;
    var h1b = false;
    var selectedSubDiv = getSubBranch(selectedDynDiv);
    var trunkDiv = getTrunk(dynDiv);
    var dynId = dynDivId.split("dyn").pop();
    var selectedDynId = selectedDynDivId.split("dyn").pop();

    if(trunkDiv.parentElement.className=="subBranch"){
      // H1a: dynDiv is child subBranch
      if(selectedSubDiv.isSameNode(trunkDiv.parentElement)){
        h1a = true;
        // deduce which one is particular 
        if(trunkDiv.className == "trunk leftTrunk"){
          var dynIdParticular = selectedDynId;
          var dynIdGeneral = dynId;
        } else if (trunkDiv.className == "trunk rightTrunk"){
          var dynIdParticular = dynId;
          var dynIdGeneral = selectedDynId;
        } 
        alert("Updating inclusion");
        $("#canvas").load("updateInclusion.php", {
          id_tod: todId,
          particular: dynIdParticular,
          general: dynIdGeneral
        });
      }
    } else if(trunkDiv.parentElement.className=="branch"){
      if(trunkDiv.parentElement.isSameNode(selectedSubDiv.parentElement.parentElement)){
        // H1b: dynDiv is child branch
        h1b = true;
        if(trunkDiv.className == "trunk leftTrunk"){
          var dynIdParticular = selectedDynId;
          var dynIdGeneral = dynId;
        } else if (trunkDiv.className == "trunk rightTrunk"){
          var dynIdParticular = dynId;
          var dynIdGeneral = selectedDynId;
        } 
        alert("Updating inclusion");
        $("#canvas").load("updateInclusion.php", {
          id_tod: todId,
          particular: dynIdParticular,
          general: dynIdGeneral
        });
      }
    } 

    // H2: selected is child
    var h2a = false;
    var h2b = false;
    var selectedTrunkDiv = getTrunk(selectedDynDiv);
    var subDiv = getSubBranch(dynDiv);
    if(selectedTrunkDiv.parentElement.className=="subBranch"){
      // H2a: dynDiv is parent by subBranch
      if(subDiv.isSameNode(selectedTrunkDiv.parentElement)){
        h2a = true;
        // deduce which one is particular 
        if(selectedTrunkDiv.className == "trunk leftTrunk"){
          var dynIdParticular = dynId;
          var dynIdGeneral = selectedDynId;
        } else if (selectedTrunkDiv.className == "trunk rightTrunk"){
          var dynIdParticular = selectedDynId;
          var dynIdGeneral = dynId;
        } 
        alert("Updating inclusion");
        $("#canvas").load("updateInclusion.php", {
          id_tod: todId,
          particular: dynIdParticular,
          general: dynIdGeneral
        });
      }
      
    } else if(selectedTrunkDiv.parentElement.className=="branch"){
      // H1b: dynDiv is child branch
      if(selectedTrunkDiv.parentElement.isSameNode(subDiv.parentElement.parentElement)){
        h2b = true;
        // deduce which one is particular 
        if(selectedTrunkDiv.className == "trunk leftTrunk"){
          var dynIdParticular = dynId;
          var dynIdGeneral = selectedDynId;
        } else if (selectedTrunkDiv.className == "trunk rightTrunk"){
          var dynIdParticular = selectedDynId;
          var dynIdGeneral = dynId;
        } 
        alert("Updating inclusion");
        $("#canvas").load("updateInclusion.php", {
          id_tod: todId,
          particular: dynIdParticular,
          general: dynIdGeneral
        });
      }
    } 
    if(!h1a &&! h1b && !h2a && !h2b){
      alert("You cannot create an inclusion between these two dynamisms.")
    }
    window.isSelectedDyn = false;
    window.selectedDynDiv = null;
    window.selectedDynDivId = null;
  } else {
    window.isSelectedDyn = true;
    window.selectedDynDiv = document.getElementById(dynDivId);
    window.selectedDynDivId = dynDivId;
  }
}
function getSubBranch(dynDiv){
  return dynDiv.parentElement.parentElement.parentElement.parentElement;
}

function getBranch(dynDiv){
  return dynDiv.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
}

function getTrunk(dynDiv){
  return dynDiv.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
}

function mouseOverDynamism(hoverId){
  mouseLeaveDynamism();
  var id = hoverId.split("dyn").pop();
  // check if id is general
  listInclusions.forEach((inclusion, i) => {
  if(inclusion.id_general==id){
    var div1 = document.getElementById(hoverId);
//
    if(hoverId!=selectedDynDivId){
      div1.setAttribute("style","background-color: rgba(255, 255, 155); border-radius:10px;");
    }
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
    if(hoverId!=selectedDynDivId){
      div1.setAttribute("style","background-color: rgba(255, 255, 155); border-radius:10px;");
    }
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
      if(id!=selectedDynDivId){
        var div = document.getElementById(id);
        div.setAttribute("style","background-color:transparent");
      }
    });
}



function mouseOverBranch(branchId){
  if(!document.getElementById("editModeChBx").checked){
    return;
  }
  var id = branchId.split("branch").pop();
  var addInBranchId = "addInBranchButton"+id;
  var addInBranchDiv = document.getElementById(addInBranchId);
  addInBranchDiv.setAttribute("style","opacity:1;");
//
  menuAddDualityId = "menuAddDuality"+id;
  $("#".menuAddDualityId).menu();
  $("#".menuAddDualityId).menu("collapse");
}

function mouseLeaveBranch(branchId){
  var id = branchId.split("branch").pop();
  var addInBranchId = "addInBranchButton"+id;
  var addInBranchDiv = document.getElementById(addInBranchId);
  addInBranchDiv.setAttribute("style", "transition:visibility 0.5s linear,opacity 0.5s linear;"); 
}

function mouseOverAddInBranchButton(buttonId){
  var c = document.getElementById("editModeChBx");
  if(!c.checked){
    return;
  }
  var id = buttonId.split("addInBranchButton").pop();
  branchId = "branch"+id;
  var branchDiv = document.getElementById(branchId);
  branchDiv.setAttribute("style","box-shadow: 3px 3px red; border-style:dashed; border-color:rgba(255, 0, 0, 1);border-width:2px;");
  //
  var buttonDiv = document.getElementById(buttonId);
  buttonDiv.setAttribute("style", "opacity:1;background-color:lightgrey;z-index:9999;");
//
  var selectorId = "selector"+id;
  var selectorDiv = document.getElementById(selectorId);
  selectorDiv.setAttribute("style", "visibility:visible;");
}

function mouseLeaveAddInBranchButton(buttonId){
  var buttonDiv = document.getElementById(buttonId);
  buttonDiv.setAttribute("style", "visibility:hidden;transition:visibility 0.5s linear,opacity 0.5s linear;"); 
//
  var id = buttonId.split("addInBranchButton").pop();
  var branchId = "branch"+id;
  
  var branchDiv = document.getElementById(branchId);
  branchDiv.setAttribute("style", "box-shadow: 3px 3px black; border-style:solid; border-color:rgba(0, 0, 0, 1);border-width:2px;");
//
  var selectorId = "selector"+id;
  var selectorDiv = document.getElementById(selectorId);
  selectorDiv.setAttribute("style", "visibility:hidden; transition:visibility 0.5s linear,opacity 0.5s linear;"); 
}

function loadMenuAddInBranch(table, container1){
  menuAddDualityContentId = "menuAddDualityContent"+container1;
  $("#"+menuAddDualityContentId).load("menuAddDuality.php", {
    container1Id: container1,
    table:table
  });   
}

function deleteTod(tod){
  // container0in1
  var sqlContainer0in1s = "";
  var len = listContainer0s.length;
  if(listContainer0s.length>1){
    sqlContainer0in1s = "DELETE FROM tbl_container_0_in_1 AS c0i1 WHERE ";
    listContainer0s.forEach((item, i) => {
      if(i<len-1){
        sqlContainer0in1s= sqlContainer0in1s+"c0i1.id_container_0 = '"+item+"' OR ";
      } else if (i==len-1){
        sqlContainer0in1s=sqlContainer0in1s+"c0i1.id_container_0 = '"+item+"';";
      }
    });
  }

  // container0in2
  var sqlContainer0in2s = "";
  var len = listContainer0s.length;
  if(listContainer0s.length>1){
    sqlContainer0in2s = "DELETE FROM tbl_container_0_in_2 AS c0i2 WHERE ";
    listContainer0s.forEach((item, i) => {
      if(i<len-1){
        sqlContainer0in2s= sqlContainer0in2s+"c0i2.id_container_0 = '"+item+"' OR ";
      } else if (i==len-1){
        sqlContainer0in2s=sqlContainer0in2s+"c0i2.id_container_0 = '"+item+"';";
      }
    });
  }
  
  // container0
  var sqlContainer0s = "DELETE FROM tbl_container_0 WHERE ";
  var len = listContainer0s.length;
  listContainer0s.forEach((item, i) => {
    if(i<len-1){
      sqlContainer0s= sqlContainer0s+"tbl_container_0.id_container_0 = '"+item+"' OR ";
    } else if (i==len-1){
      sqlContainer0s=sqlContainer0s+"tbl_container_0.id_container_0 = '"+item+"';";
    }
  });

  // container1
  var sqlContainer1s = "DELETE FROM tbl_container_1 WHERE ";
  var len = listContainer1s.length;
  listContainer1s.forEach((item, i) => {
    if(i<len-1){
      sqlContainer1s= sqlContainer1s+"tbl_container_1.id_container_1 = '"+item+"' OR ";
    } else if (i==len-1){
      sqlContainer1s=sqlContainer1s+"tbl_container_1.id_container_1 = '"+item+"';";
    }
  });

  // container2
  var sqlContainer2s = "";
  if(listContainer2s!=null){
    sqlContainer2s = "DELETE FROM tbl_container_2 AS c2 WHERE ";
    var len = listContainer2s.length;
    listContainer2s.forEach((item, i) => {
      if(i<len-1){
        sqlContainer2s= sqlContainer2s+"c2.id_container_2 = '"+item+"' OR ";
      } else if (i==len-1){
        sqlContainer2s=sqlContainer2s+"c2.id_container_2 = '"+item+"';";
      }
    });
  } 
  
  $("#canvas").load("deleteTod.php", {
    tod: tod,
    sqlDeleteContainer0in1s: sqlContainer0in1s,
    sqlDeleteContainer0in2s: sqlContainer0in2s,
    sqlDeleteContainer2s: sqlContainer2s,
    sqlDeleteContainer1s: sqlContainer1s,
    sqlDeleteContainer0s: sqlContainer0s
  });  
  
}
function setLists(){
  $("#dualityEditor").draggable(); // move 
  $("#dualityEditor").hide(); // move
  var d = document.getElementById('dynamismsArrayDiv').innerHTML;
  if(d){
    listDynamisms = JSON.parse(d);
  }
  var i = document.getElementById('inclusionsArrayDiv').innerHTML;
  if(i){
    listInclusions = JSON.parse(i);
  }
  listContainer0s = JSON.parse(document.getElementById('container0sArrayDiv').innerHTML);
  listContainer1s = JSON.parse(document.getElementById('container1sArrayDiv').innerHTML);
  var c2 = document.getElementById('container2sArrayDiv').innerHTML;
  if(c2){
    listContainer2s = JSON.parse(c2);
  }
}