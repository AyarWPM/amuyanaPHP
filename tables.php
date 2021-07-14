<?php
ob_start(); // to make header work
// global variables declarations
include 'includes/header.php';
  $currentPage="tables";
  $option;
  $currentTable;

  if(isset($_GET['option'])){
    $option=$_GET['option'];
  }
  if(isset($_GET["id"])){
    $currentTable = $_GET["id"];
  }

  $id_container_0;
  $dynArray = array();
  $container0sAggregated=array();
  $container0sArrayEncoded;
  $container2sAggregated=array();
  $container2sArrayEncoded;
  $container1sAggregated=array();
  $container1sArrayEncoded;

// content
  echo '<div class="tables">';
  if(!isset($option)){
    getTableSelector();
    getCanvas();
  } else {
    if($option== "Rename"){
      $label = $_GET['label'];
      $sqlUpdateTable = "UPDATE tbl_tod SET label ='".$label."' 
                        WHERE id_tod = '".$currentTable."';";
      if(!mysqli_query($conn,$sqlUpdateTable)){
        echo "Error at updating table.<br>";
      }
      $url = "tables.php?id=".$currentTable."&option=Open";
      header( "refresh:0; url=$url" );
      exit();
    } else if($option == "Open"){
      getTableSelector();
      if(isset($currentTable)){
        getCanvas();
      } else {
        echo '<div class="canvas" id="canvas">';
        echo '<div class="message">Select a table from the list or create a new one.</div>';
        echo '</div>';
      }
    } else if ($option=="New"){
      createNewTod();
    } else if($option=="Delete"){
      getTableSelector();
      if(isset($currentTable)){
        getCanvas();
        echo "<script>deleteTod(".$currentTable.")</script>";
        header( "refresh:2; url=tables.php" );
        exit();
      } else {
        echo '<div class="canvas" id="canvas">';
        echo '<div class="message">Select a table from the list.</div>';
        echo '</div>';
      }
    }
  }

  function createNewTod(){
    global $conn;
    global $currentTable;
    $newContainer0Id;
    $sqlNewContainer0 = "INSERT INTO tbl_container_0 (id_container_0) VALUES (null);";
    if(!mysqli_query($conn,$sqlNewContainer0)){
      echo "Error at creating new container0.<br>";
    } else {
      $newContainer0Id = mysqli_insert_id($conn);
    }
    $sqlNewContainer1 = "INSERT INTO tbl_container_1 (id_container_0) VALUES ('".$newContainer0Id."');";
    
    if(!mysqli_query($conn,$sqlNewContainer1)){
      echo "Error at creating new container1.<br>";
    } else {
      $newContainer1Id = mysqli_insert_id($conn);
      $sqlUpdateNewContainer1 = "UPDATE tbl_container_1 
                                SET branch_order = '".$newContainer1Id."' 
                                WHERE id_container_1 = '".$newContainer1Id."';";
      if(!mysqli_query($conn,$sqlUpdateNewContainer1)){
        echo "Error at updating new container1.<br>";
      }
    }
    
    $sqlNewTable = "INSERT INTO tbl_tod (id_container_0) 
                    VALUES ('".$newContainer0Id."');";

    if(!mysqli_query($conn,$sqlNewTable)){
      echo "Error at creating new table.<br>";
      
    } else {
      // $newTodId = mysqli_insert_id($conn);
      $currentTable = mysqli_insert_id($conn);
      $label = "New Table of deductions ".$currentTable;
      $sqlUpdateTable = "UPDATE tbl_tod SET label ='".$label."' 
                        WHERE id_tod = '".$currentTable."';";
      if(!mysqli_query($conn,$sqlUpdateTable)){
        echo "Error at updating table.<br>";
      } else {
        getTableSelector();
        getCanvas();
      }
    }
    $url = "tables.php?id=".$currentTable."&option=Open";
    header( "refresh:0; url=$url" );
    exit();
  }



  function getTableSelector(){
    global $conn;
    global $currentTable;
    global $id_container_0;
    $tableName = "";
    echo '<div class="select">';
    echo '<form class="selectForm" action="tables.php" method="get">';
    echo '<select name="id">';
    $sql = "SELECT * FROM tbl_tod;";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
      while($row=mysqli_fetch_assoc($result)){
        if($row["id_tod"]==$currentTable){
          $id_container_0 = $row['id_container_0'];
          $tableName = $row['label'];
          echo '<option value="'.$row["id_tod"].'" selected >'.$row["label"].'</option>';
        } else {
          echo '<option value="'.$row["id_tod"].'" >'.$row["label"].'</option>';
        }
      }
    }

    echo '</select>';
    echo '<input type="submit" value="Open" name="option">';
    echo '<input type="submit" id="newTableBtn" value="New" name="option">';
    echo '<input type="submit" id="deleteTableBtn" value="Delete" name="option">';
    echo '<input id="renameTableTxt" name="label" value="'.$tableName.'">';
    echo '<input type="submit" id="renameTableBtn" value="Rename" name="option">';
    
    echo '</form>';
    echo '<span id="hintBox">Enable edit mode</span><input type="checkbox" id="editModeChBx" value="Enable" onclick="toggleEditMode(1)">';
    echo '<span id="hint">(shortcut: ctrl+enter)</span>';
    echo '</div>';
  }

  function getCanvas(){
    global $option;
    global $id_container_0;
    global $dynArray;
    global $dynArrayEncoded;
    global $container0sArrayEncoded;
    global $container1sArrayEncoded;
    global $container2sArrayEncoded;
    global $inclusionsArrayEncoded;
    global $conn;
    global $currentTable;
    
    echo '<div class="canvas" id="canvas">';
    if(!isset($option)){
      echo '<div class="message">To begin open an existing table or create a new one.</div>';
    } else if (isset($option)){
      if($option=="Delete"){
        echo '<div style="display:none;">';
      }
      getTree($id_container_0);
      if($option=="Delete"){
        echo '</div">';
        echo "Deleting Table, please wait...";
      }
      echo '<div id="dualityEditor" class="ui-widget-content"></div>';
      echo '<div id="container0sArrayDiv" style="display:none;">'.$container0sArrayEncoded.'</div>';
      echo '<div id="container1sArrayDiv" style="display:none;">'.$container1sArrayEncoded.'</div>';
      echo '<div id="container2sArrayDiv" style="display:none;">'.$container2sArrayEncoded.'</div>';
      $dynArrayEncoded = json_encode($dynArray); // move after getTrunk() in code.php after dvlping delete Tod
      echo '<div id="dynamismsArrayDiv" style="display:none;">'.$dynArrayEncoded.'</div>';

      // get data of inclusions, encode
      $sql = "SELECT id_particular, id_general FROM tbl_inclusion
      WHERE id_tod=".$currentTable.";";
      $result = mysqli_query($conn,$sql);
      $datas=array();
      if($result){
        if(mysqli_num_rows($result) > 0){
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
      }
      $inclusionsArrayEncoded = json_encode($datas);
      echo '<div id="inclusionsArrayDiv" style="display:none;">'.$inclusionsArrayEncoded.'</div>';
      echo '<script>setLists();</script>';
    }
    echo '</div>'; // canvas
  }
  echo '</div>'; // table
?>
<script>
  var checkBox = document.getElementById("editModeChBx");
  if(checkBox.checked){
    $("#deleteTableBtn").fadeIn(0);
    $("#newTableBtn").fadeIn(0);
    $("#renameTableBtn").fadeIn(0);
    $("#renameTableTxt").fadeIn(0);
    $("#hint").fadeIn(0);
  } else {
    $("#deleteTableBtn").fadeOut(0);
    $("#newTableBtn").fadeOut(0);
    $("#renameTableBtn").fadeOut(0);
    $("#renameTableTxt").fadeOut(0);
    $("#hint").fadeOut(0);
  }
  window.onload = function() {
   document.getElementsByTagName('body')[0].onkeyup = function(e) { 
      var ev = e || event;
      if(ev.keyCode == 13 && ev.ctrlKey) {
        var checkBox = document.getElementById("editModeChBx");
        checkBox.checked = true;
        toggleEditMode(0);
      }
   }
};
</script>