<?php 
  include('includes/dbh.inc.php');
  $todId = $_POST['tod'];
  $sqlDC0 = $_POST['sqlDeleteContainer0s'];
  $sqlDC1 = $_POST['sqlDeleteContainer1s'];
  $sqlDC2 = $_POST['sqlDeleteContainer2s'];
  $sqlDC0i1 = $_POST['sqlDeleteContainer0in1s'];
  $sqlDC0i2 = $_POST['sqlDeleteContainer0in2s'];

  // delete container0in1
  if(!empty($sqlDC0i1)){
    if(!mysqli_query($conn,$sqlDC0i1)){
      echo "error deleting c0i1.<br>";
    }
  }

  // delete container0in2
  if(!empty($sqlDC0i2)){
    if(!mysqli_query($conn,$sqlDC0i2)){
      echo "error deleting c0i2.<br>";
    }
  }

  // delete container2
  if(!empty($sqlDC2)){
    if(!mysqli_query($conn,$sqlDC2)){
      echo "error deleting c2.<br>";
    }
  }

  // delete container1
  if(!mysqli_query($conn,$sqlDC1)){
    echo "Error deleting c1.<br>";
    echo $sqlDC1;
    echo "<br>";
  }

  // delete tod
  $sqlDeleteTod = "DELETE FROM tbl_tod WHERE id_tod = '".$todId."';";
  if(!mysqli_query($conn,$sqlDeleteTod)){
    echo "Error deleting TOD.<br>";
  }

  // delete container0
  if(!mysqli_query($conn,$sqlDC0)){
    echo "Error deleting c0.<br>";
    echo $sqlDC0;
    echo "<br>";
  }
  
  ?>