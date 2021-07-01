<?php
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $id_fcc = $_POST['id_fcc'];
  // $container_2 = $_POST['c2'];
  // $option = $_POST['optionDualityEditor'];

  // if option = "Save"
  $name = $_POST['fccName'];
  $description = $_POST['fccDescription'];

  $element = $_POST['element'];
  $antiElement = $_POST['antiElement'];

  $positiveLabel = $_POST['positiveLabel'];
  $positiveDescription = $_POST['positiveDescription'];
  $negativeLabel = $_POST['negativeLabel'];
  $negativeDescription = $_POST['negativeDescription'];
  $symmetricLabel = $_POST['symmetricLabel'];
  $symmetricDescription = $_POST['symmetricDescription'];

  $sqlFcc = "UPDATE tbl_fcc SET name = '".$name."', description = '".$description."' WHERE id_fcc='".$id_fcc."';";
  $sqlElement = "UPDATE tbl_element SET symbol = '".$element."' WHERE id_fcc='".$id_fcc."' AND polarity='0';";
  $sqlAntiElement = "UPDATE tbl_element SET symbol = '".$antiElement."' WHERE id_fcc='".$id_fcc."' AND polarity='1';";
  
  $sqlPositiveDynamism = "UPDATE tbl_dynamism SET proposition='".$positiveLabel."', description='".$positiveDescription."' WHERE id_fcc='".$id_fcc."' AND orientation='0';";
  $sqlNegativeDynamism = "UPDATE tbl_dynamism SET proposition='".$negativeLabel."', description='".$negativeDescription."' WHERE id_fcc='".$id_fcc."' AND orientation='1';";
  $sqlSymmetricDynamism = "UPDATE tbl_dynamism SET proposition='".$symmetricLabel."', description='".$symmetricDescription."' WHERE id_fcc='".$id_fcc."' AND orientation='2';";
  
  if(!mysqli_query($conn,$sqlFcc)){
    echo "FCC: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }
  if(!mysqli_query($conn,$sqlElement)){
    echo "Element: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }
  if(!mysqli_query($conn,$sqlAntiElement)){
    echo "Anti-Element: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }
  if(!mysqli_query($conn,$sqlPositiveDynamism)){
    echo "Positive dynamism: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }
  if(!mysqli_query($conn,$sqlNegativeDynamism)){
    echo "Negative dynamism: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }
  if(!mysqli_query($conn,$sqlSymmetricDynamism)){
    echo "Symmetric dynamism: There's been a problem updating the information, please try again or contact ayarportugal@gmail.com<br>";
  }


  $url = "tables.php?id=".$table."&option=Open";
  header("refresh:0; url=$url");
  exit();
  ?>