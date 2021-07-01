var listDynamisms;
var listGenerals;
var listParticulars;
var listInclusions;
var listContainer0s;
var listContainer1s;
var listContainer2s;

function mouseOverDynamism(hoverId){
  mouseLeaveDynamism();

  var id = hoverId.split("dyn").pop();
  // check if id is general
  listInclusions.forEach((inclusion, i) => {
  if(inclusion.id_general==id){
    var div1 = document.getElementById(hoverId);
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
    });
}

function mouseOverBranch(branchId){
  var id = branchId.split("branch").pop();
  var addInBranchId = "addInBranchButton"+id;
  var addInBranchDiv = document.getElementById(addInBranchId);
  addInBranchDiv.setAttribute("style","opacity:1;");

  menuAddDualityId = "menuAddDuality"+id;
  $("#".menuAddDualityId).menu();
  $("#".menuAddDualityId).menu("collapse");
}

function mouseLeaveBranch(branchId){
  var id = branchId.split("branch").pop();
  var addInBranchId = "addInBranchButton"+id;
  var addInBranchDiv = document.getElementById(addInBranchId);
  addInBranchDiv.setAttribute("style","opacity:0;");

}

function mouseOverAddInBranchButton(buttonId){
  var id = buttonId.split("addInBranchButton").pop();
  branchId = "branch"+id;
  var branchDiv = document.getElementById(branchId);
  branchDiv.setAttribute("style","border-color:rgba(255, 0, 0, 1);border-width:2px;");
  
  var buttonDiv = document.getElementById(buttonId);
  buttonDiv.setAttribute("style", "opacity:1;background-color:lightgrey;z-index:9999;");

  var selectorId = "selector"+id;
  var selectorDiv = document.getElementById(selectorId);
  selectorDiv.setAttribute("style", "visibility:visible;overflow:visible;");
}

function mouseLeaveAddInBranchButton(buttonId){
  var buttonDiv = document.getElementById(buttonId);
  buttonDiv.setAttribute("style", "opacity:0;");

  var id = buttonId.split("addInBranchButton").pop();
  var branchId = "branch"+id;
  var branchDiv = document.getElementById(branchId);
  branchDiv.setAttribute("style", "border-color:rgba(255, 0, 0, 0);border-width:2px;");

  var selectorId = "selector"+id;
  var selectorDiv = document.getElementById(selectorId);
  selectorDiv.setAttribute("style", "visibility:hidden;");  
}

function loadMenuAddInBranch(table, container1){
    menuAddDualityContentId = "menuAddDualityContent"+container1;
    $("#"+menuAddDualityContentId).load("menuAddDuality.php", {
      container1Id: container1,
      table:table
    });   
}

function openMenuAddDualityInBranch(buttonId){
  // var id = buttonId.split("addInBranchButton").pop();
  // branchId = "branch"+id;

  // var buttonDiv = document.getElementById(buttonId);
  // buttonDiv.setAttribute("style", "opacity:1;background-color:lightgrey;z-index:9999;");

  // var selectorId = "selector"+id;
  // var selectorDiv = document.getElementById(selectorId);
  // selectorDiv.setAttribute("style", "visibility:visible;overflow:visible;");
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
  var sqlContainer0s = "DELETE FROM tbl_container_0 AS c0 WHERE ";
  var len = listContainer0s.length;
  listContainer0s.forEach((item, i) => {
    if(i<len-1){
      sqlContainer0s= sqlContainer0s+"c0.id_container_0 = '"+item+"' OR ";
    } else if (i==len-1){
      sqlContainer0s=sqlContainer0s+"c0.id_container_0 = '"+item+"';";
    }
  });

  // container1
  var sqlContainer1s = "DELETE FROM tbl_container_1 AS c1 WHERE ";
  var len = listContainer1s.length;
  listContainer1s.forEach((item, i) => {
    if(i<len-1){
      sqlContainer1s= sqlContainer1s+"c1.id_container_1 = '"+item+"' OR ";
    } else if (i==len-1){
      sqlContainer1s=sqlContainer1s+"c1.id_container_1 = '"+item+"';";
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
  
  // $(document.documentElement).load("deleteTod.php", {
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