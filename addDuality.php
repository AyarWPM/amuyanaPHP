<?php
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $container1Id = $_POST['container1Id'];
  $duality = $_POST['duality'];
  echo "<script>alert(".$duality.")<script>";
  if(empty($duality)){
    $newId;
    $fccName = "New Duality ";
    $fccDescription = "Add a description.";
    $sqlNewFcc = "INSERT INTO tbl_fcc (name, description) VALUES ('".$fccName."', '".$fccDescription."');";
    global $conn;
    if(!mysqli_query($conn,$sqlNewFcc)){
      echo "Fcc NOT created.";
    } else {
      $newId = mysqli_insert_id($conn);
      $duality = mysqli_insert_id($conn);
      // update with new FCC
      $fccName = $fccName.$newId;
      $sqlUpdateFcc = "UPDATE tbl_fcc SET name = '".$fccName."' WHERE id_fcc='".$newId."';";
      if(!mysqli_query($conn,$sqlUpdateFcc)){
        echo "Fcc NOT updated.<br>";
      }
    }

    $element = "e".$newId;
    $antiElement = "e".$newId;
    $sqlNewElement = "INSERT INTO tbl_element (symbol, polarity, id_fcc) VALUES ('".$element."',0,'".$newId."');";
    $sqlNewAntiElement = "INSERT INTO tbl_element (symbol, polarity, id_fcc) VALUES ('".$antiElement."',1,'".$newId."');";
    if(!mysqli_query($conn,$sqlNewElement)){
      echo "Element NOT created.<br>";
    }
    if(!mysqli_query($conn,$sqlNewAntiElement)){
      echo "Anti-Element NOT created.<br>";
    }
  
    $PDP = "Positive orientation of Duality ".$newId;
    $PDD = "Add a description.";
    $sqlNewPD = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('0','".$PDP."','".$PDD."','".$newId."');";
    if(!mysqli_query($conn,$sqlNewPD)){
      echo "Positive Dynamism NOT created.<br>";
    }
  
    $NDP = "Negative orientation of Duality ".$newId;
    $NDD = "Add a description.";
    $sqlNewND = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('1','".$NDP."','".$NDD."','".$newId."');";
    if(!mysqli_query($conn,$sqlNewND)){
      echo "Negative Dynamism NOT created.<br>";
    }
  
    $SDP = "Symmetric orientation of Duality ".$newId;
    $SDD = "Add a description.";
    $sqlNewSD = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('2','".$SDP."','".$SDD."','".$newId."');";
    if(!mysqli_query($conn,$sqlNewSD)){
      echo "Symmetric Dynamism NOT created.<br>";
    }
  } else {
    //$newId = $_POST["duality"];
  }

  // condition0: if it is an empty branch, create left and right trunks for the branch and container1s inside
  $sqlCondition0 = "SELECT id_container_2 FROM tbl_container_2 WHERE tbl_container_2.id_container_1 = '".$container1Id."';";
  $results = mysqli_query($conn,$sqlCondition0);
  if($results->num_rows == 0){
    // create trunks for the branch and branches inside trunks
  // left side      
    $sqlNC0L = "INSERT INTO tbl_container_0 (id_container_0) VALUES (null);";
    if(!mysqli_query($conn,$sqlNC0L)){
      echo "Error at creating C0.<br>";
    }
    $nC0LId=mysqli_insert_id($conn);
    $sqlNC1L = "INSERT INTO tbl_container_1 (id_container_0) VALUES ('".$nC0LId."')";
    $nC1LID;
    if(!mysqli_query($conn,$sqlNC1L)){
      echo "Error at creating C1.<br>";
    } else {
      // update
      $nC1LID = mysqli_insert_id($conn);
      $sqlUpdateNC1L = "UPDATE tbl_container_1 SET branch_order = '".$nC1LID."' WHERE id_container_1='".$nC1LID."'";
      if(!mysqli_query($conn,$sqlUpdateNC1L)){
        echo "Error at updating NC1.<br>";
      }
    }
    $sqlNC0i1L = "INSERT INTO tbl_container_0_in_1 (id_container_0, id_container_1, side) 
                VALUES ('".$nC0LId."','".$container1Id."', '0');";
    if(!mysqli_query($conn,$sqlNC0i1L)){
      echo "Error at creating c0i1L.<br>";
    }

// right side
    $sqlNC0R = "INSERT INTO tbl_container_0 (id_container_0) VALUES (null);";
    if(!mysqli_query($conn,$sqlNC0R)){
      echo "Error at creating C0.<br>";
    }
    $nC0RId=mysqli_insert_id($conn);
    $sqlNC1R = "INSERT INTO tbl_container_1 (id_container_0) VALUES ('".$nC0RId."')";
    $nC1RID;
    if(!mysqli_query($conn,$sqlNC1R)){
      echo "Error at creating C1.<br>";
    } else {
      // update
      $nC1RID = mysqli_insert_id($conn);
      $sqlUpdateNC1R = "UPDATE tbl_container_1 SET branch_order = '".$nC1RID."' WHERE id_container_1='".$nC1RID."'";
      if(!mysqli_query($conn,$sqlUpdateNC1R)){
        echo "Error at updating NC1.<br>";
      }
    }
    $sqlNC0i1R = "INSERT INTO tbl_container_0_in_1 (id_container_0, id_container_1, side) 
                VALUES ('".$nC0RId."','".$container1Id."', '1');";
    if(!mysqli_query($conn,$sqlNC0i1R)){
      echo "Error at creating c0i1R.<br>";
    }
  }

  // create container2
  $sqlContainer2 = "INSERT INTO tbl_container_2 (id_fcc, id_container_1, sub_branch_order) 
                    VALUES ('".$duality."','".$container1Id."','0');";
  if(!mysqli_query($conn,$sqlContainer2)){
    echo "Container2 NOT created.<br>";
  }
  $newContainer2Id=mysqli_insert_id($conn);

  // update sub_branch_order ".$newContainer2Id."
  $sqlUpdateContainer2 = "UPDATE tbl_container_2 SET sub_branch_order = '".$newContainer2Id."' WHERE id_container_2='".$newContainer2Id."';";
  if(!mysqli_query($conn,$sqlUpdateContainer2)){
    echo "Container2 NOT updated.<br>";
  }

  $sqlLeftContainer0 = "INSERT INTO tbl_container_0 (id_container_0) VALUES ( null );";
  if(!mysqli_query($conn,$sqlLeftContainer0)){
    echo "Left Container0 NOT created.<br>";
  }
  $newContainer0LeftId =  mysqli_insert_id($conn); 
  $sqlLeftContainer1 = "INSERT INTO tbl_container_1 (id_container_0) VALUES ( '".$newContainer0LeftId."' );";
  if(!mysqli_query($conn,$sqlLeftContainer1)){
    echo "Left Container1 NOT created.<br>";
  } else {
    $newId = mysqli_insert_id($conn);
    $sqlUpdateC1 = "UPDATE tbl_container_1 SET branch_order = '".$newId."' WHERE id_container_1='".$newId."'";
    if(!mysqli_query($conn,$sqlUpdateC1)){
      echo "Error at updating NC1.<br>";
    }
  }

  $sqlC0inC2Left = "INSERT INTO tbl_container_0_in_2 (id_container_0, id_container_2, side) VALUES ('".$newContainer0LeftId."', '".$newContainer2Id."', '0');";
  if(!mysqli_query($conn,$sqlC0inC2Left)){
    echo "Container0in2 Left NOT created.<br>";
  } 

  $sqlRightContainer0 = "INSERT INTO tbl_container_0 (id_container_0) VALUES ( null );";
  if(!mysqli_query($conn,$sqlRightContainer0)){
    echo "Right Container0 NOT created.<br>";
  }
  $newContainer0RightId =  mysqli_insert_id($conn); 
  $sqlRightContainer1 = "INSERT INTO tbl_container_1 (id_container_0) VALUES ( '".$newContainer0RightId."' );";
  if(!mysqli_query($conn,$sqlRightContainer1)){
    echo "Right Container1 NOT created.<br>";
  } else {
    // update branch_order
    $newId = mysqli_insert_id($conn);
    $sqlUpdateC1 = "UPDATE tbl_container_1 SET branch_order = '".$newId."' WHERE id_container_1='".$newId."'";
    if(!mysqli_query($conn,$sqlUpdateC1)){
      echo "Error at updating NC1.<br>";
    }
  }
  $sqlC0inC2Right = "INSERT INTO tbl_container_0_in_2 (id_container_0, id_container_2, side) VALUES ('".$newContainer0RightId."', '".$newContainer2Id."', '1');";
  if(!mysqli_query($conn,$sqlC0inC2Right)){
    echo "Container0in2 Right NOT created.<br>";
  }

  
  // new tbl_tod_has_tbl_fcc
  $sqlTodFcc = "INSERT INTO tbl_tod_has_fcc (id_tod, id_fcc)  
                VALUES ('".$table."', '".$duality."');";
  if(!mysqli_query($conn,$sqlTodFcc)){
    echo "mysql error 2.";
  }
  
  $url ="tables.php?id=".$table."&option=Open";
  header("refresh:2; url=$url");
  exit();
?>
