<?php
  // include_once 'includes/dbh.inc.php';

  function getTree($id_container_0){
    echo '<div id="tree" class="tree">';
    // echo '<svg class="line" width="500" height="500"><line x1="50" y1="50" x2="350" y2="350" stroke="black"/></svg>';
    getTrunk($id_container_0,0);
    echo '</div>';
  }
// side=-1,0,1 respectively left trunk, root Trunk and right trunk
  function getTrunk($id_container_0, $side){
    // if $side=-1 echo '<div class="trunk ">';
    echo ($side==-1) ? '<div class="trunk leftTrunk">' : (($side==0) ? '<div class="trunk rootTrunk">' : (($side==1) ? '<div class="trunk rightTrunk">' : ''));
    // if($side==-1){echo 'side -1';}
    // if($side==0){echo 'side 0';}
    // if($side==1){echo 'side 1';}

    global $conn;
    $sql = "SELECT tbl_container_1.id_container_1 FROM tbl_container_1 WHERE tbl_container_1.id_container_0=".$id_container_0." ORDER BY branch_order;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }
    // for now only one branch per trunk, so there's only going to be one result
    if($isResult){
      foreach($datas as $data){
        getBranch($data['id_container_1']);
      }
    } else {
      echo ($side==0) ? 'This table is empty' : '';
    }
    echo '</div>';
  }

  function getBranch($id_branch){
    echo '<div class="branch">';
    // echo 'branch '.$id_branch;
    //checking left and right trunks
    global $conn;
    $sqlLeft = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='.$id_branch.' AND tbl_container_0_in_1.side = 0;';
    $sqlRight = 'SELECT tbl_container_0.id_container_0 FROM tbl_container_0
    INNER JOIN tbl_container_0_in_1 ON tbl_container_0.id_container_0 = tbl_container_0_in_1.id_container_0
    INNER JOIN tbl_container_1 ON tbl_container_1.id_container_1 = tbl_container_0_in_1.id_container_1
    WHERE tbl_container_1.id_container_1='.$id_branch.' AND tbl_container_0_in_1.side = 1;';
    $sqlSubBranches = 'SELECT id_container_2, id_fcc, sub_branch_order
    FROM tbl_container_2 WHERE tbl_container_2.id_container_1='.$id_branch.' ORDER BY sub_branch_order;';
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
      echo 'this part of the code has been used 0';
      echo '<div class="trunk">nodata</div>';
    }

    echo '<div class="subBranchContainer">';
        // echo all subBranches
        // forall
        // getSubbranch($id_subBranch);
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
            getSubbranch($data['id_container_2'],$data['id_fcc']);
          }
        }
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
      echo 'this part of the code has been used 0';
      echo '<div class="trunk">nodata</div>';
    }

    echo '</div>';
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
    $sqlFcc = 'SELECT id_container_2, id_fcc, sub_branch_order
    FROM tbl_container_2 WHERE tbl_container_2.id_container_1='.$id_branch.' ORDER BY sub_branch_order;';
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
      echo 'this part of the code has been used 0';
      echo '<div class="trunk">nodata</div>';
    }

    echo '<div class="fruitContainer">';

    getFruit($id_fcc);
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
      echo 'this part of the code has been used 0';
      echo '<div class="trunk">nodata</div>';
    }
    echo '</div>';
  }

  function getFruit($id_fcc){
    global $conn;
    $name="";
    $positive="";
    $negative="";
    $symmetric="";
    // FCC
    $sql = "SELECT name, description FROM tbl_fcc WHERE tbl_fcc.id_fcc=".$id_fcc.";";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $name=$row['name'];
      }
    }

    // POSITIVE
    $sql = "SELECT proposition FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=0;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $positive=$row['proposition'];
      }
    }

    // NEGATIVE
    $sql = "SELECT proposition FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=1;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $negative=$row['proposition'];
      }
    }

    // SYMMETRIC
    $sql = "SELECT proposition FROM tbl_dynamism
    WHERE tbl_dynamism.id_fcc=".$id_fcc." AND tbl_dynamism.orientation=2;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $symmetric=$row['proposition'];
      }
    }

    echo '<div class="fruit">';
    echo '<div class="fruitHeader">';
    echo $name;
    echo '</div>';
    echo '<div class="fruitBracket">';
    echo '<img src="/includes/bracket.png" alt="bracket">';
    echo '</div>';
    echo '<div class="fruitFormulation">';
    echo '<div class="formulation">'.$positive.'</div>';
    echo '<div class="formulation">'.$negative.'</div>';
    echo '<div class="formulation">'.$symmetric.'</div>';
    echo '</div>';
    echo '<div class="fruitConnector">';
    echo '<div class="connector"></div>';
    echo '<div class="connector"></div>';
    echo '<div class="connector"></div>';
    echo '</div>';
    echo '</div>';;
  }

?>
