<?php
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $container1Id = $_POST['container1Id'];
  $selectorId = "selector".$container1Id;
  echo '<div id="'.$selectorId.'" style="visibility:hidden;">';
  echo '<form action="addDuality.php" method="post">';
  echo '<input type="text" hidden="true" value="'.$container1Id.'" name="container1Id">';
  echo '<input type="text" hidden="true" value="'.$table.'" name="table">';
  echo '<select  class="selectorAddDuality"   name="duality">';
  echo '<option value="0" name="newDuality">New duality</option>';
  $name = "";
  $description = "";
  // for each table...
  $tods = array();
  $sql = "SELECT * FROM tbl_tod;";
  $result = mysqli_query($conn,$sql);
  if($result){
    if(mysqli_num_rows($result) > 0){
      while($row=mysqli_fetch_assoc($result)){
        $tods[] = $row;
      }
    }
  } else {
    echo "error sql";
  }  
  foreach ($tods as $tod) {
    $title = $tod['label'];
    if($table === $tod['id_tod']){
      echo '<option disabled>-'.$title.'- (This Table)</option>';
    } else {
      echo '<option disabled>-'.$title.'- </option>';
    }
    $idTod = $tod['id_tod'];
    $sql = "SELECT * FROM tbl_fcc 
            INNER JOIN tbl_tod_has_fcc ON tbl_tod_has_fcc.id_fcc = tbl_fcc.id_fcc
            WHERE tbl_tod_has_fcc.id_tod = $idTod;";
    $result = mysqli_query($conn,$sql);
    if($result){
      if(mysqli_num_rows($result) > 0){
          while($row=mysqli_fetch_assoc($result)){
              $id = $row['id_fcc'];
              $name= $row['name'];
              $description = $row['description'];
              echo '<option value="'.$id.'">('.$id.') '.$name.'</option>';
            }
      }
    } 
  }
  echo '<option disabled>Deleted dualities</option>';
  
  $sql = "SELECT * FROM tbl_fcc WHERE tbl_fcc.id_fcc NOT IN 
          (SELECT tt.id_fcc FROM tbl_tod_has_fcc AS tt 
          WHERE tt.id_FCC = tbl_fcc.id_fcc);";
  $resultFccNotInTod = mysqli_query($conn,$sql);
  if($resultFccNotInTod){
    if(mysqli_num_rows($resultFccNotInTod) > 0){
      while($row=mysqli_fetch_assoc($resultFccNotInTod)){
          $id = $row['id_fcc'];
          $name= $row['name'];
          $description = $row['description'];
          echo '<option value="'.$id.'">('.$id.') '.$name.'</option>';
        }
  }
  } else {
    echo "Error mysql.";
  }
  echo '</select>';
  echo '<input class="selectorAddDualityBtn" type="submit" value="Add">';
  echo '</form>';
  echo '</div>';
?>