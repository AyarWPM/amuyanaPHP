<?php
  // was in code.php

  function getTree($id_container_0){
    echo '<div id="tree" class="tree">';
    getTrunk($id_container_0,0);
    echo '</div>';
  }
// side=-1,0,1 respectively left trunk, root Trunk and right trunk
  function getTrunk($id_container_0, $side){
    global $container0sAggregated;
    global $container0sArrayEncoded;
    $container0sAggregated[] = $id_container_0;
    $container0sArrayEncoded = json_encode($container0sAggregated);
    echo ($side==-1) ? '<div class="trunk leftTrunk">' : (($side==0) ? '<div class="trunk rootTrunk">' : (($side==1) ? '<div class="trunk rightTrunk">' : ''));
    
    global $conn;
    $sql = "SELECT id_container_1, branch_order 
    FROM tbl_container_1 WHERE id_container_0='".$id_container_0."' ORDER BY branch_order;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }

    global $container1sAggregated;
    global $container1sArrayEncoded;
    // for now only one branch per trunk, so there's only going to be one result
    if($isResult){
      foreach($datas as $data){
        $container1sAggregated[] = $data["id_container_1"];
        // array_merge($container1sAggregated,$data['id_container_1']);
        $container1sArrayEncoded = json_encode($container1sAggregated);
        getBranch($data['id_container_1']);
      }
    }
    echo '</div>';
  }

  function getBranch($id_branch){
    $id_branch_div = "branch".$id_branch;
    echo '<div onmouseover="mouseOverBranch(this.id)" onmouseleave="mouseLeaveBranch(this.id)" class="branch" id="'.$id_branch_div.'">';
    //checking left and right trunks
    global $conn;
    $sqlLeft = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='.$id_branch.' AND tbl_container_0_in_1.side = 0;';
    $result = mysqli_query($conn,$sqlLeft);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }
    if($isResult){
      foreach($datas as $data){
        getTrunk($data['id_container_0'],-1);
      }
    }

    global $container2sAggregated;
    global $container2sArrayEncoded;
    echo '<div class="subBranchContainer">';
        // echo all subBranches
        // forall
        // getSubbranch($id_subBranch);
        $sqlSubBranches = 'SELECT id_container_2, id_fcc, sub_branch_order
        FROM tbl_container_2 WHERE tbl_container_2.id_container_1='.$id_branch.' ORDER BY sub_branch_order ASC;';
        $result = mysqli_query($conn,$sqlSubBranches);
        $datas=array();
        $isResult = false;
        if(mysqli_num_rows($result) > 0){
          $isResult = true;
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        if($isResult){
          foreach($datas as $data){
            $container2sAggregated[] = $data["id_container_2"];
            // array_merge($container1sAggregated,$data['id_container_1']);
            $container2sArrayEncoded = json_encode($container2sAggregated);
            getSubbranch($data['id_container_2'],$data['id_fcc']);
          }
        }

    echo '</div>';
    $sqlRight = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='.$id_branch.' AND tbl_container_0_in_1.side = 1;';
    $result = mysqli_query($conn,$sqlRight);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }
    if($isResult){
      foreach($datas as $data){
        getTrunk($data['id_container_0'],1);
      }
    }
    echo '</div>';
    $id_addInBranchButton_div = "addInBranchButton".$id_branch;
    echo '<div id="'.$id_addInBranchButton_div.'" class="addInBranchButton" onmouseover="mouseOverAddInBranchButton(this.id)" 
    onmouseleave="mouseLeaveAddInBranchButton(this.id)" onclick="openMenuAddDualityInBranch(this.id)">'; 
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
    $sqlLeft = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_2 ON tbl_container_0.id_container_0 = tbl_container_0_in_2.id_container_0
    INNER JOIN tbl_container_2 ON tbl_container_2.id_container_2 = tbl_container_0_in_2.id_container_2
    WHERE tbl_container_2.id_container_2='.$id_subBranch.' AND tbl_container_0_in_2.side = 0;';
    $sqlRight = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_2 ON tbl_container_0.id_container_0 = tbl_container_0_in_2.id_container_0
    INNER JOIN tbl_container_2 ON tbl_container_2.id_container_2 = tbl_container_0_in_2.id_container_2
    WHERE tbl_container_2.id_container_2='.$id_subBranch.' AND tbl_container_0_in_2.side = 1;';

    $result = mysqli_query($conn,$sqlLeft);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }
    if($isResult){
      foreach($datas as $data){
        getTrunk($data['id_container_0'],-1);
      }
    } else {
      // add empty div for styling
      // echo 'this part of the code has been used 0';
      // echo '<div class="trunk">nodata</div>';
    }

    echo '<div class="fruitContainer">';

    getFruit($id_fcc,$id_subBranch);
    echo '</div>';

    $result = mysqli_query($conn,$sqlRight);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }
    if($isResult){
      foreach($datas as $data){
        getTrunk($data['id_container_0'],1);
      }
    } else {
      // add empty div for styling
      // echo 'this part of the code has been used 0';
      // echo '<div class="trunk">nodata</div>';
    }
    
    echo '</div>';
  }

  function getFruit($id_fcc,$id_subBranch){
    global $conn;
    // Fruit menu
    // echo '<div class="fruitMenu">
    // <div class="fruitMenuItem">&#9998;</div>
    // <div class="fruitMenuItem">A</div>
    // </div>';
    // Fruit content

    // ELEMENT AND ANTI-ELEMENT
    $element = "";
    $antiElement ="";
    $sql = 'SELECT symbol, polarity FROM tbl_element WHERE tbl_element.id_fcc='.$id_fcc.';';
    $result = mysqli_query($conn,$sql);
    $datas2=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas2[] = $row;
      }
    }
    foreach($datas2 as $data2){
      if($data2['polarity']==0){
        $element=$data2["symbol"];
      }else if($data2['polarity']==1){
        $antiElement=$data2["symbol"];
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
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $name=$row['name'];
        $description=$row['description'];
      }
    }

    // THREE ORIENTATIONS
    global $dynArray;
    $positive="";
    $descriptionPositive="";
    $id_positive=0;
    $negative="";
    $descriptionNegative="";
    $id_negative=0;
    $symmetric="";
    $descriptionSymmetric="";
    $id_symmetric=0;
    // POSITIVE
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=0;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $positive=$row['proposition'];
        $descriptionPositive=$row['description'];
        $id=$row['id_dynamism'];
        $id_positive='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
        $dynArray[]=$id_positive; // add the id to the global array
      }
    }

    // NEGATIVE
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=1;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $negative=$row['proposition'];
        $descriptionNegative=$row['description'];
        $id=$row['id_dynamism'];
        $id_negative='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
        $dynArray[]=$id_negative; // add the id to the global array
      }
    }

    // SYMMETRIC
    $sql = "SELECT id_dynamism, proposition, description FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=2;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $symmetric=$row['proposition'];
        $id=$row['id_dynamism'];
        $descriptionSymmetric=$row['description'];
        $id_symmetric='sub'.$id_subBranch.'dyn'.$id; // we'll store with the format 'sub###dyn###'
        $dynArray[]=$id_symmetric; // add the id to the global array
      }
    }

    echo '<div class="fruit">';
    echo '<div class="fruitHeader">';
    // By convention, id = type [name,positive,...]+id_fcc
    global $currentTable;
    echo '<div id="dual'.$id_fcc.'" onclick="editDuality(this.id ,'.$id_subBranch.', '.$currentTable.')" class="fruitName">'.$name.'</div><div class="formulation"> ('.$element.'&middot;'.$antiElement.')</div>';
    echo '</div>';
    echo '<div class="fruitBracket">';
    echo '<img src="includes/bracket.png" alt="bracket">';
    echo '</div>';
    echo '<div class="fruitFormulation">';
    echo '<div onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" id="'.$id_positive.'" class="proposition">'.$positive.'</div>';
    echo '<div onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionNegative.'" onmouseleave="mouseLeaveDynamism()" id="'.$id_negative.'" class="proposition">'.$negative.'</div>';
    echo '<div onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionSymmetric.'" onmouseleave="mouseLeaveDynamism()" id="'.$id_symmetric.'" class="proposition">'.$symmetric.'</div>';
    echo '</div>';
    echo '<div class="fruitConnector">';
    // echo '<div class="connector"></div>';
    // echo '<div class="connector"></div>';
    // echo '<div class="connector"></div>';
    echo '</div>';
    echo '</div>';
  }
  //  function refresh(){
  //   header("refresh:2; url=tables.php?id=".$table."&option=Open");
  //  }
?>
