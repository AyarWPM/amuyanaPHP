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
    if($option == "Open"){
      getTableSelector();
      if(isset($currentTable)){
        getCanvas();
      } else {
        echo '<div class="canvas" id="canvas">';
        echo "Select a table from the list or create a new one.";
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
        echo "Select a table from the list.";
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
    
    echo '<div class="select">';
    echo '<form action="tables.php" method="get">';
    echo '<select name="id">';
    $sql = "SELECT * FROM tbl_tod;";
    
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;

    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }

    foreach($datas as $data){
      if($data["id_tod"]==$currentTable){
        $id_container_0 = $data['id_container_0'];
        echo '<option value="'.$data["id_tod"].'" selected >'.$data["label"].'</option>';
      } else {
        echo '<option value="'.$data["id_tod"].'" >'.$data["label"].'</option>';
      }
    }
    echo '</select>';
    echo '<input type="submit" value="Open" name="option">';
    echo '<input type="submit" value="New" name="option">';
    echo '<input type="submit" value="Delete" name="option">';
    echo '</form>';
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
      echo '<script>setLists()</script>';
    }
    echo '</div>'; // canvas
  }
  echo '</div>'; // table
?>
