<?php
  function getTree($id_container_0){
    echo '<div id="tree" class="tree">';
    getTrunk($id_container_0,0);
    echo '</div>';
  }
// side=-1,0,1 respectively left trunk, root Trunk and right trunk
  function getTrunk($id_container_0, $side){
    global $conn;
    global $container0sAggregated;
    global $container0sArrayEncoded;
    global $container1sAggregated;
    global $container1sArrayEncoded;
    global $container2sAggregated;
    global $container2sArrayEncoded;

    $container0sAggregated[] = $id_container_0;
    $container0sArrayEncoded = json_encode($container0sAggregated);

    echo ($side==-1) ? '<div class="trunk leftTrunk">' : (($side==0) ? '<div class="trunk rootTrunk">' : (($side==1) ? '<div class="trunk rightTrunk">' : ''));
    $sql = "SELECT id_container_1, branch_order 
    FROM tbl_container_1 WHERE id_container_0='".$id_container_0."' ORDER BY branch_order;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    if(!$result){
      echo "Error selecting branch.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          // for now only one branch per trunk, so there's only going to be one result
          $container1sAggregated[] = $row["id_container_1"];
          getBranch($row['id_container_1']);
        }
        $container1sArrayEncoded = json_encode($container1sAggregated);
      }
    }
    echo '</div>';
  }

  function getBranch($id_branch){
    $id_branch_div = "branch".$id_branch;
    echo '<div onmouseover="mouseOverBranch(this.id)" onmouseleave="mouseLeaveBranch(this.id)" class="branch" id="'.$id_branch_div.'">';
    //checking left and right trunks
    global $conn;
    $sqlLeft = "SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='".$id_branch."' AND tbl_container_0_in_1.side = '0';";
    $result = mysqli_query($conn,$sqlLeft);
    if(!$result){
      echo "Error selecting branches.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          getTrunk($row['id_container_0'],-1);
        }
      }
    }
    
    echo '<div class="subBranchContainer">';
    $sqlSubBranches = "SELECT id_container_2, id_fcc, sub_branch_order
    FROM tbl_container_2 WHERE tbl_container_2.id_container_1='".$id_branch."' ORDER BY sub_branch_order ASC;";
    $result = mysqli_query($conn,$sqlSubBranches);
    if(!$result){
      echo "Error selecting subBranches.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
        $container2sAggregated[] = $row["id_container_2"];
        $container2sArrayEncoded = json_encode($container2sAggregated);
        getSubbranch($row['id_container_2'],$row['id_fcc']);
        }
      }
    }

    echo '</div>';
    $sqlRight = "SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='".$id_branch."' AND tbl_container_0_in_1.side = '1';";
    $result = mysqli_query($conn,$sqlRight);
    if(!$result){
      echo "Error selecting right trunks.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          getTrunk($row['id_container_0'],1);
        }
      }
    }

    echo '</div>';
    $id_addInBranchButton_div = "addInBranchButton".$id_branch;
    echo '<div id="'.$id_addInBranchButton_div.'" class="addInBranchButton" onmouseover="mouseOverAddInBranchButton(this.id)" 
    onmouseleave="mouseLeaveAddInBranchButton(this.id)">'; 
    echo '<div>&#10133;</div>';
    $id_menuAddDualityContent_div = "menuAddDualityContent".$id_branch;
    echo '<div id="'.$id_menuAddDualityContent_div.'">';
    echo '</div>';
    echo '</div>';
  
    // load script
    global $currentTable;
    echo '<script>loadMenuAddInBranch('.$currentTable.', '.$id_branch.')</script>';
  }


  function getSubbranch($id_subBranch, $id_fcc){
    echo '<div class="subBranch">';
    // echo 'SubBranch '.$id_subBranch;
    global $conn;
    $sqlLeft = "SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_2 ON tbl_container_0.id_container_0 = tbl_container_0_in_2.id_container_0
    INNER JOIN tbl_container_2 ON tbl_container_2.id_container_2 = tbl_container_0_in_2.id_container_2
    WHERE tbl_container_2.id_container_2='".$id_subBranch."' AND tbl_container_0_in_2.side = '0';";
    $sqlRight = "SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_2 ON tbl_container_0.id_container_0 = tbl_container_0_in_2.id_container_0
    INNER JOIN tbl_container_2 ON tbl_container_2.id_container_2 = tbl_container_0_in_2.id_container_2
    WHERE tbl_container_2.id_container_2='".$id_subBranch."' AND tbl_container_0_in_2.side = '1';";

    $result = mysqli_query($conn,$sqlLeft);
    if(!$result){
      echo "error selecting leftSubBranch.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          getTrunk($row['id_container_0'],-1);
        }
      }
    }

    echo '<div class="fruitContainer">';
    getFruit($id_fcc,$id_subBranch);
    echo '</div>';
    $result = mysqli_query($conn,$sqlRight);
    if(!$result){
      echo "Error selecting rightSubBranch.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          getTrunk($row['id_container_0'],1);
        }
      }
    }
    echo '</div>';
  }

  function getFruit($id_fcc,$id_subBranch){
    global $conn;
    // ELEMENT AND ANTI-ELEMENT
    $element = "";
    $antiElement ="";
    $sql = 'SELECT symbol, polarity FROM tbl_element WHERE tbl_element.id_fcc='.$id_fcc.';';
    $result = mysqli_query($conn,$sql);
    $datas2=array();
    $isResult = false;
    if(!$result){
      echo "error selecting element.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          if($row['polarity']==0){
            $element=$row["symbol"];
          }else if($row['polarity']==1){
            $antiElement=$row["symbol"];
          }
        }
      }
    }
    if($element==$antiElement){
      $antiElement='<div class="element bar">'.$element.'</div>';
    }
    $element = '<div class="element">'.$element.'</div>';

    // FCC
    $name="";
    $description="";
    $sql = "SELECT name, description FROM tbl_fcc WHERE tbl_fcc.id_fcc=".$id_fcc.";";
    $result = mysqli_query($conn,$sql);
    if(!$result){
      echo "Error selecting FCC.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          $name=$row['name'];
          $description=$row['description'];
        }
      }
    }

    // THREE ORIENTATIONS
    global $dynArray;
    $positive="";
    $descriptionPositive="";
    $divIdPositiveDyn;
    $negative="";
    $descriptionNegative="";
    $divIdNegativeDyn;
    $symmetric="";
    $descriptionSymmetric="";
    $divIdSymmetricDyn;
    // POSITIVE
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc='".$id_fcc."' AND tbl_dynamism.orientation='0';";
    $result = mysqli_query($conn,$sql);

    if(!$result){
      echo "Error selecting positive dynamism.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          $positive=$row['proposition'];
          $descriptionPositive=$row['description'];
          $id=$row['id_dynamism'];
          $divIdPositiveDyn='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
          $dynArray[]=$divIdPositiveDyn; // add the id to the global array
        }
      }
    }

    // NEGATIVE
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc='".$id_fcc."' AND tbl_dynamism.orientation='1';";
    $result = mysqli_query($conn,$sql);

    if(!$result){
      echo "Error selecting negative dynamism.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          $negative=$row['proposition'];
          $descriptionNegative=$row['description'];
          $id=$row['id_dynamism'];
          $divIdNegativeDyn='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
          $dynArray[]=$divIdNegativeDyn; // add the id to the global array
        }
      }
    }
    
    // SYMMETRIC
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc='".$id_fcc."' AND tbl_dynamism.orientation='2';";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(!$result){
      echo "Error selecting symmetric dynamism.<br>";
    } else {
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          $symmetric=$row['proposition'];
          $id=$row['id_dynamism'];
          $descriptionSymmetric=$row['description'];
          $divIdSymmetricDyn='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
          $dynArray[]=$divIdSymmetricDyn; // add the id to the global array
        }
      }
    }
    

    echo '<div class="fruit">';
    echo '<div class="fruitHeader">';
    // By convention, id = type [name,positive,...]+id_fcc
    global $currentTable;
    echo '<div id="dual'.$id_fcc.'" onclick="editDuality(this.id ,'.$id_subBranch.', '.$currentTable.')" class="fruitName"  title="'.$description.'" >'.$name.'</div><div class="formulation"> ('.$element.'&middot;'.$antiElement.')</div>';
    echo '</div>';
    echo '<div class="fruitBracket">';
    echo '<img src="includes/bracket.png" alt="bracket">';
    echo '</div>';
    echo '<div class="fruitFormulation">';
    echo '<div id="'.$divIdPositiveDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionPositive.'" onclick="clickDynamism('.$currentTable.',this.id)" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$positive.'</div>';
    echo '<div id="'.$divIdNegativeDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionNegative.'" onclick="clickDynamism('.$currentTable.',this.id)" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$negative.'</div>';
    echo '<div id="'.$divIdSymmetricDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionSymmetric.'" onclick="clickDynamism('.$currentTable.',this.id)" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$symmetric.'</div>';
    echo '</div>';
    echo '<div class="fruitConnector">';
    // echo '<div class="connector"></div>';
    // echo '<div class="connector"></div>';
    // echo '<div class="connector"></div>'; // if i draw lines
    echo '</div>';
    echo '</div>';
  }
?>
