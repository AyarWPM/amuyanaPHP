<?php
// globals meta
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $container1Id = $_POST['container1Id'];
  $idFcc = $_POST['duality'];
  $newIdFcc;
  //globals
  $fccName;
  $fccDescription;
  $element;
  $antiElement;
  $PDP;
  $PDD;
  $NDP;
  $NDD;
  $SDP;
  $SDD;

  if($idFcc>0) {
    $sql = "SELECT * FROM tbl_tod_has_fcc 
          WHERE tbl_tod_has_fcc.id_tod = ".$table." AND tbl_tod_has_fcc.id_fcc = ".$idFcc.";";
    $resultTodHasFcc = mysqli_query($conn,$sql);
    if($resultTodHasFcc){
      if(mysqli_num_rows($resultTodHasFcc) > 0){
        // if this fcc is in a table, copy it
        $sql = "SELECT name, description FROM tbl_fcc WHERE tbl_fcc.id_fcc = ".$idFcc.";";
        $result = mysqli_query($conn,$sql);
        if($result){
          if(mysqli_num_rows($result) > 0){
            while($row=mysqli_fetch_assoc($result)){
              $fccName= $row['name'];
              $fccDescription = $row['description'];
            }
          }
        } else {
          echo "mysql error";
        }
        // INSERT IN TABLE COPY
        $sql = "INSERT INTO tbl_fcc (name, description) VALUES ('".$fccName."', '".$fccDescription."');";
        if(!mysqli_query($conn,$sql)){
          echo "Fcc NOT created.";
        } else {
          $newIdFcc = mysqli_insert_id($conn);
        }
        copyAttributes();
      }else {
        // if it is not in a table, add it
        $newIdFcc = $idFcc;
      }
    } else {
      echo "mysql error";
    }
  } else if($idFcc==0){
    // create attributes
    $fccName = "New Duality ";
    $fccDescription = "Add a description.";
    $sqlNewFcc = "INSERT INTO tbl_fcc (name, description) VALUES ('".$fccName."', '".$fccDescription."');";
    if(!mysqli_query($conn,$sqlNewFcc)){
      echo "Fcc NOT created.";
    } else {
      $newIdFcc = mysqli_insert_id($conn);
      // update with new FCC name
      $fccName = $fccName.$newIdFcc;
      $sqlUpdateFcc = "UPDATE tbl_fcc SET name = '".$fccName."' WHERE id_fcc='".$newIdFcc."';";
      if(!mysqli_query($conn,$sqlUpdateFcc)){
        echo "Fcc NOT updated.<br>";
      }
    }
    setNewAttributes();
  }
  if(mysqli_num_rows($resultTodHasFcc) !== 0){
    insertValues();
  }
  createContainers();

  function copyAttributes(){
    global $conn;
    global $idFcc;
    global $element;
    global $antiElement;
    global $PDP;
    global $PDD;
    global $NDP;
    global $NDD;
    global $SDP;
    global $SDD;

    $sql = "SELECT symbol, polarity FROM tbl_element WHERE tbl_element.id_fcc = ".$idFcc.";";
    $result = mysqli_query($conn,$sql);
    if($result){
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          if($row['polarity']==0) {
            $element = $row['symbol'];
          } else if($row['polarity']==1){
            $antiElement = $row['symbol'];
          }
        }
      }
    } else {
      echo "mysql error";
    }    

    $sql = "SELECT proposition, description, orientation FROM tbl_dynamism WHERE tbl_dynamism.id_fcc = ".$idFcc.";";
    $result = mysqli_query($conn,$sql);
    if($result){
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          if($row['orientation']==0) {
            $PDP = $row['proposition'];
            $PDD = $row['description'];
          } else if($row['orientation']==1){
            $NDP = $row['proposition'];
            $NDD = $row['description'];
          } else if($row['orientation']==2){
            $SDP = $row['proposition'];
            $SDD = $row['description'];
          }
        }
      }
    } else {
      echo "mysql error";
    } 
  }

  function setNewAttributes(){
    global $newIdFcc;
    global $element;
    global $antiElement;
    global $PDP;
    global $PDD;
    global $NDP;
    global $NDD;
    global $SDP;
    global $SDD;

    $element = "e".$newIdFcc;
    $antiElement = "e".$newIdFcc;
    $PDP = "Positive orientation of Duality ".$newIdFcc;
    $PDD = "Add a description.";
    $NDP = "Negative orientation of Duality ".$newIdFcc;
    $NDD = "Add a description.";
    $SDP = "Symmetric orientation of Duality ".$newIdFcc;
    $SDD = "Add a description.";
  }

  function insertValues(){
    global $conn;
    global $newIdFcc;
    global $element;
    global $antiElement;
    global $PDP;
    global $PDD;
    global $NDP;
    global $NDD;
    global $SDP;
    global $SDD;

    $sqlNewElement = "INSERT INTO tbl_element (symbol, polarity, id_fcc) VALUES ('".$element."',0,'".$newIdFcc."');";
    $sqlNewAntiElement = "INSERT INTO tbl_element (symbol, polarity, id_fcc) VALUES ('".$antiElement."',1,'".$newIdFcc."');";
    if(!mysqli_query($conn,$sqlNewElement)){
      echo "Element NOT created.<br>";
    }
    if(!mysqli_query($conn,$sqlNewAntiElement)){
      echo "Anti-Element NOT created.<br>";
    }
    $sqlNewPD = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('0','".$PDP."','".$PDD."','".$newIdFcc."');";
    if(!mysqli_query($conn,$sqlNewPD)){
      echo "Positive Dynamism NOT created.<br>";
    }    
    $sqlNewND = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('1','".$NDP."','".$NDD."','".$newIdFcc."');";
    if(!mysqli_query($conn,$sqlNewND)){
      echo "Negative Dynamism NOT created.<br>";
    }    
    $sqlNewSD = "INSERT INTO tbl_dynamism (orientation, proposition, description, id_fcc) 
    VALUES ('2','".$SDP."','".$SDD."','".$newIdFcc."');";
    if(!mysqli_query($conn,$sqlNewSD)){
      echo "Symmetric Dynamism NOT created.<br>";
    }
  }

  function createContainers(){
    global $conn;
    global $newIdFcc;
    global $table;
    global $container1Id;
    
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
    $sqlNC1L = "INSERT INTO tbl_container_1 (id_container_0) VALUES ('".$nC0LId."');";
    $nC1LID;
    if(!mysqli_query($conn,$sqlNC1L)){
      echo "Error at creating C1.<br>";
    } else {
      // update
      $nC1LID = mysqli_insert_id($conn);
      $sqlUpdateNC1L = "UPDATE tbl_container_1 SET branch_order = '".$nC1LID."' WHERE id_container_1='".$nC1LID."';";
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
    $sqlNC1R = "INSERT INTO tbl_container_1 (id_container_0) VALUES ('".$nC0RId."');";
    $nC1RID;
    if(!mysqli_query($conn,$sqlNC1R)){
      echo "Error at creating C1.<br>";
    } else {
      // update
      $nC1RID = mysqli_insert_id($conn);
      $sqlUpdateNC1R = "UPDATE tbl_container_1 SET branch_order = '".$nC1RID."' WHERE id_container_1='".$nC1RID."';";
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
                    VALUES ('".$newIdFcc."','".$container1Id."','0');";
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
    $sqlUpdateC1 = "UPDATE tbl_container_1 SET branch_order = '".$newId."' WHERE id_container_1='".$newId."';";
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
    $sqlUpdateC1 = "UPDATE tbl_container_1 SET branch_order = '".$newId."' WHERE id_container_1='".$newId."';";
    if(!mysqli_query($conn,$sqlUpdateC1)){
      echo "Error at updating NC1.<br>";
    }
  }
  $sqlC0inC2Right = "INSERT INTO tbl_container_0_in_2 (id_container_0, id_container_2, side) VALUES ('".$newContainer0RightId."', '".$newContainer2Id."', '1');";
  if(!mysqli_query($conn,$sqlC0inC2Right)){
    echo "Container0in2 Right NOT created.<br>";
  }

  $sqlTodFcc = "INSERT INTO tbl_tod_has_fcc (id_tod, id_fcc)  
                VALUES ('".$table."', '".$newIdFcc."');";
  if(!mysqli_query($conn,$sqlTodFcc)){
    echo "mysql error 2.";
  }
  $url ="tables.php?id=".$table."&option=Open";
  echo '<script>location.replace("'.$url.'");</script>';
  }
?>
