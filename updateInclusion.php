<?php
include('includes/dbh.inc.php');    
    $id_particular = $_POST["particular"];
    $id_general = $_POST["general"];
    $id_tod = $_POST["id_tod"];
    
    $sql = "SELECT id_inclusion FROM tbl_inclusion AS i 
            WHERE id_particular = '".$id_particular."' AND id_general = '".$id_general."';";
    global $conn;
    $result = mysqli_query($conn,$sql);

    if($result){
      if(mysqli_num_rows($result)>0){
        // if there's inclusion, remove it
        $inclusion;
        while($row=mysqli_fetch_assoc($result)){
          $inclusion = $row['id_inclusion'];
        }
        $sqlRemove = "DELETE FROM tbl_inclusion WHERE id_inclusion = '".$inclusion."';";
        if(!mysqli_query($conn,$sqlRemove)){
          echo "mysql error, place1";
        }
      } else if (mysqli_num_rows($result) == 0){
        $sqlNewInclusion = "INSERT INTO tbl_inclusion (id_tod, id_particular, id_general) 
                            VALUES ('".$id_tod."','".$id_particular."','".$id_general."');";
        if(!mysqli_query($conn,$sqlNewInclusion)){
          echo "mysql error, place 3";
        }
      }
      
    } else {
      echo "mysql error, place2";
    }
    echo "<script>location.reload();</script>";
?>