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
  echo '<option value="" name="newDuality">New duality</option>';
  $name = "";
  $description = "";
  // $sql = "SELECT * FROM tbl_fcc WHERE tbl_fcc.id_fcc NOT IN 
  //         (SELECT tbl_fcc.id_fcc FROM tbl_fcc  
  //         INNER JOIN tbl_tod_has_fcc AS tf ON tf.id_fcc = tbl_fcc.id_fcc
  //         WHERE tf.id_tod = '".$table."');";
  $sql = "SELECT * FROM tbl_fcc WHERE tbl_fcc.id_fcc NOT IN 
          (SELECT tbl_fcc.id_fcc FROM tbl_fcc  
          INNER JOIN tbl_container_2 AS c2 ON c2.id_fcc = tbl_fcc.id_fcc
          INNER JOIN tbl_container_1 AS c1 ON c1.id_container_1 = c2.id_container_1
          WHERE c1.id_container_1 = '".$container1Id."');";
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
  echo '</select>';
  echo '<input class="selectorAddDualityBtn" type="submit" value="Add">';
  echo '</form>';
  echo '</div>';
?>