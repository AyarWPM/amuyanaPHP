var listDynamisms;
var listGenerals;
var listParticulars;
var listInclusions;
function mouseOver(hoverId){
  mouseLeave();
  // the hovered dyn, is in the list?
  //    no: do nothing
  //    yes:
  //        getInclusions of this table
  //        search hovered dyn in generals, hightlight it and any particular
  //        search hovered dyn in particulars, highlight it and any general

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

function mouseLeave(){
    listDynamisms.forEach((id, i) => {
      var div = document.getElementById(id);
        div.setAttribute("style","background-color:transparent");

    });

}
