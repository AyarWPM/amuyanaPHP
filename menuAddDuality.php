<?php
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $container1Id = $_POST['container1Id'];
  $selectorId = "selector".$container1Id;
 
  echo '<div class="selectorAddDuality" id="'.$selectorId.'" style="visibility:hidden;">';
  echo '<form action="addDuality.php" method="post">';
  echo '<input type="text" hidden="true" value="'.$container1Id.'" name="container1Id">';
  echo '<input type="text" hidden="true" value="'.$table.'" name="table">';
  echo '<select name="duality">';
  echo '<option value="" name="newDuality">New duality</option>';
  $name = "";
  $description = "";
  $sql = "SELECT * FROM tbl_fcc WHERE tbl_fcc.id_fcc NOT IN 
  (SELECT tbl_fcc.id_fcc FROM tbl_fcc INNER JOIN tbl_container_2 ON tbl_container_2.id_fcc = tbl_fcc.id_fcc 
  INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_2.id_container_1
  WHERE tbl_container_1.id_container_1=".$container1Id.");";
  $result = mysqli_query($conn,$sql);
  
  if(mysqli_num_rows($result) > 0){
      while($row=mysqli_fetch_assoc($result)){
          $id = $row['id_fcc'];
          $name= $row['name'];
          $description = $row['description'];
          echo '<option value="'.$id.'">('.$id.') '.$name.'</option>';
        }
  }
?>

</select>
<input class="selectorAddDualityBtn" type="submit" value="Add">
</form>
</div>