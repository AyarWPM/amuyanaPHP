<?php
  include('includes/dbh.inc.php');
  $table = $_POST['table'];
  $id_fcc = $_POST['id_fcc'];
  $id_container_2= $_POST['id_container_2'];
  echo "<b>Removing Duality ".$id_fcc. " from table ".$table." and container2: ".$id_container_2."</b><br>";
  
  // condition1: check if its subBranches's trunks have FCCs
  //    true: check condition2:
  //    false: inform user, abort.
  //    c2d = "container2 of destination; c2o= "container2 of origin" (where we test)
  $condition1;
  $sqlCondition1 = "SELECT fd.id_fcc, fd.name FROM tbl_fcc AS fd 
                    INNER JOIN tbl_container_2 AS c2d ON c2d.id_fcc = fd.id_fcc
                    INNER JOIN tbl_container_1 AS c1d ON c1d.id_container_1 = c2d.id_container_1
                    INNER JOIN tbl_container_0 AS c0d ON c0d.id_container_0 = c1d.id_container_0
                    INNER JOIN tbl_container_0_in_2 AS c0i2 ON c0i2.id_container_0 = c0d.id_container_0
                    INNER JOIN tbl_container_2 AS c2o ON c2o.id_container_2 = c0i2.id_container_2
                    WHERE c2o.id_container_2 = '".$id_container_2."';";
$result = mysqli_query($conn,$sqlCondition1);
if(mysqli_num_rows($result) > 0){
  $condition1 = false;
  echo 'Condition1 = false : Cannot remove Duality '.$id_fcc.', the subBranches have dualities.<br>';
    while($row=mysqli_fetch_assoc($result)){
        $id = $row['id_fcc'];
        $name = $row['name'];
    }
} else if (mysqli_num_rows($result) == 0){
  // No FCCS
  $condition1 = true;
  echo "Condition1 = true : There are no FCCs it the subBranches.<br>";
}

  // condition2: check if if has branches' trunks have FCCs
  //    false: check condition3
  //    true: check condition4
  //    c2d = "container2 of destination; c2o= "container2 of origin" (where we test)
  $condition2;
  $dualitiesInBranches = array();
  if($condition1){
    $sqlCondition2 = "SELECT fd.id_fcc, fd.name, c0i1.side FROM tbl_fcc AS fd 
    INNER JOIN tbl_container_2 AS c2d ON c2d.id_fcc = fd.id_fcc 
    INNER JOIN tbl_container_1 AS c1d ON c1d.id_container_1 = c2d.id_container_1 
    INNER JOIN tbl_container_0 AS c0d ON c0d.id_container_0 = c1d.id_container_0 
    INNER JOIN tbl_container_0_in_1 AS c0i1 ON c0i1.id_container_0 = c0d.id_container_0 
    INNER JOIN tbl_container_1 AS c1o ON c1o.id_container_1 = c0i1.id_container_1 
    INNER JOIN tbl_container_2 AS c2o ON c2o.id_container_1 = c1o.id_container_1 
    WHERE c2o.id_container_2 = '".$id_container_2."';";

    $result = mysqli_query($conn,$sqlCondition2);
    if(mysqli_num_rows($result) > 0){
      $condition2 = false;
      echo "Condition2 = false : The branches of container2=".$id_container_2." have dualities.<br>";
      while($row=mysqli_fetch_assoc($result)){
        $dualitiesInBranches[] = $row;
      }
    } else if (mysqli_num_rows($result) == 0){
      $condition2 = true;
      echo "Condition2 = true : The branches of container2=".$id_container_2." do not have dualities.<br>";
    }
  }
   
  // condition3: check if there are inclusions between itself and the branches' FCCs
  //    yes: inform user, abort.
  //    no: check condition4
  $condition3;
  $sqlCondition3L;
  $sqlCondition3R;
  $leftDualities=array();
  $rightDualities = array();
  if(isset($condition2) && !$condition2){
    foreach($dualitiesInBranches as $dualityInBranch){
      if($dualityInBranch['side']==0){
        $leftDualities[] = $dualityInBranch;
      } else if ($dualityInBranch['side']==1){
        $rightDualities[] = $dualityInBranch;
      }
    }
    // check left side
    $sqlCondition3L = "SELECT id_inclusion FROM tbl_inclusion AS i 
                        INNER JOIN tbl_dynamism AS dp ON dp.id_dynamism = i.id_particular 
                        INNER JOIN tbl_dynamism AS dg ON dg.id_dynamism = i.id_general 
                        INNER JOIN tbl_fcc as fp ON fp.id_fcc = dp.id_fcc 
                        INNER JOIN tbl_fcc as fg ON fg.id_fcc = dg.id_fcc 
                        WHERE (i.id_tod = '".$table."' AND fp.id_fcc = '".$id_fcc."') AND ( ";
    $i = 0;
    $len = count($leftDualities);
    foreach ($leftDualities as $leftDuality) {
      $generalFcc = $leftDuality['id_fcc'];
      if ($i < $len-1) {
        $sqlCondition3L = $sqlCondition4L."fg.id_fcc = '".$generalFcc."' OR ";
      } else if ($i == $len - 1) {
        $sqlCondition3L = $sqlCondition4L."fg.id_fcc = '".$generalFcc."' );";
      }
      $i++;
    }

    $result = mysqli_query($conn,$sqlCondition3L);
    if(mysqli_num_rows($result)>0){
      $condition3 = false;
      echo "Condition3 = false : There are inclusions with left side. Cannot remove duality.<br>";
    } else if (mysqli_num_rows($result) == 0){
      // if no left duality in the left trunk of the branch has inclusion, test the right ones
      $sqlCondition3R = "SELECT id_inclusion FROM tbl_inclusion AS i 
                        INNER JOIN tbl_dynamism AS dp ON dp.id_dynamism = i.id_particular 
                        INNER JOIN tbl_dynamism AS dg ON dg.id_dynamism = i.id_general 
                        INNER JOIN tbl_fcc as fp ON fp.id_fcc = dp.id_fcc 
                        INNER JOIN tbl_fcc as fg ON fg.id_fcc = dg.id_fcc 
                        WHERE (i.id_tod = '".$table."' AND fg.id_fcc = '".$id_fcc."') AND ( "; 
      $j = 0;
      $len2 = count($rightDualities);
      foreach($rightDualities as $rightDuality){
        $particular = $rightDuality['id_fcc'];
        if($j<$len2-1){
          $sqlCondition3R = $sqlCondition3R."fp.id_fcc = '".$particular."' OR ";
        } else if ($j == $len2-1){
          $sqlCondition3R = $sqlCondition3R."fp.id_fcc = '".$particular."' );";
        }
        $j++;
      }

      $result = mysqli_query($conn,$sqlCondition3R);
      if(mysqli_num_rows($result)>0){
        $condition3 = false;
        echo "Condition3 = false : There are inclusions with right side. Cannot remove duality.<br>";
      } else if (mysqli_num_rows($result) == 0){
        $condition3 = true;
        echo "Condition3 = true : No inclusions with dualities in branches.<br>";
        // echo $sqlCondition3L."<br>";
        // echo $sqlCondition3R;
      }
    }
  }
  
  // condition4: check if it in root trunk 
  //             true: check condition6
  //             false: check condition5
  //    
  $condition4;
  $condition4a;
  $condition4b;
  $dualitiesInParents = array();
  if((isset($condition1) && $condition1) || (isset($condition2) && !$condition2 && $condition3)){
    //      h1: it is inside a branch
    $sqlCondition4Branch = "SELECT fd.id_fcc, c0i1.side FROM tbl_fcc AS fd 
                          INNER JOIN tbl_container_2 AS c2d ON c2d.id_fcc = fd.id_fcc 
                          INNER JOIN tbl_container_1 AS c1d ON c2d.id_container_1 = c1d.id_container_1 
                          INNER JOIN tbl_container_0_in_1 AS c0i1 ON c1d.id_container_1 = c0i1.id_container_1 
                          INNER JOIN tbl_container_1 AS c1o ON c0i1.id_container_0 = c1o.id_container_0 
                          INNER JOIN tbl_container_2 AS c2o ON c1o.id_container_1 = c2o.id_container_1 
                          WHERE c2o.id_container_2 = '".$id_container_2."';";
    $result = mysqli_query($conn,$sqlCondition4Branch);
    if($result){
      if(mysqli_num_rows($result)>0){
        $condition4a = true;
        echo "Condition4a = true : it is in a branch.<br>";

        while($row=mysqli_fetch_assoc($result)){
          $dualitiesInParents[] = $row;
        }

      } else if (mysqli_num_rows($result)==0){
        $condition4a = false;
        echo "Condition4a = false : No branches have this duality.<br>";
      }
    }
    // h2: it is inside a subBranch
    $sqlCondition4SubBranch = "SELECT fd.id_fcc, c0i2.side FROM tbl_fcc AS fd 
                              INNER JOIN tbl_container_2 AS c2d ON c2d.id_fcc = fd.id_fcc
                              INNER JOIN tbl_container_0_in_2 AS c0i2 ON c2d.id_container_2 = c0i2.id_container_2 
                              INNER JOIN tbl_container_1 AS c1o ON c0i2.id_container_0 = c1o.id_container_0 
                              INNER JOIN tbl_container_2 AS c2o ON c2o.id_container_1 = c1o.id_container_1 
                              WHERE c2o.id_container_2 = '".$id_container_2."';";
    $result = mysqli_query($conn,$sqlCondition4SubBranch);
    if($result){
      if(mysqli_num_rows($result)>0){
        $condition4b = true;
        echo "Condition4b = true : it is in a subBranch.<br>";
        while($row=mysqli_fetch_assoc($result)){
          $dualitiesInParents[] = $row;
        }
      } else if (mysqli_num_rows($result)==0){
        $condition4b = false;
        echo "Condition4b = false : No subBranches have this duality.<br>";
      }
    } 
   
    if((isset($condition4a) && $condition4a) || (isset($condition4b) && $condition4b)){
      $condition4 = false;
      echo "Condition4 = false : Duality is in a branch or subBranch.<br>";
    } 
    if(isset($condition4a) && !$condition4a && isset($condition4b) && !$condition4b){
      $condition4 = true;
      echo "Condition4 = true : Duality is in root trunk.<br>";
    }
  }
 
  // condition5: check if there's an inclusion between itself and parents FCCs
  //             true: check condition6
  //             false: inform user, abort
  // 
  //             First if it is in a left trunk,
  //             Second if it is in a right trunk
  $condition5;
  $condition5a;
  $condition5b;
  $leftOfParentDualities = array();
  $rightOfParentDualities = array();
 if(isset($condition1) && $condition1 && isset($condition4) && !$condition4){
    // check inclusions
    foreach($dualitiesInParents as $dualityInParent){
      
      if($dualityInParent['side']==0){
        
        $leftOfParentDualities[] = $dualityInParent;
        //echo print_r($leftOfParentDualities);
      } else if ($dualityInParent['side']==1){
        $rightOfParentDualities[] = $dualityInParent;
      }
    }

    // left side (the parent is in the right)
    $sqlCondition5L = "SELECT id_inclusion FROM tbl_inclusion AS i 
                      INNER JOIN tbl_dynamism AS dp ON dp.id_dynamism = i.id_particular 
                      INNER JOIN tbl_dynamism AS dg ON dg.id_dynamism = i.id_general 
                      INNER JOIN tbl_fcc AS fp ON fp.id_fcc = dp.id_fcc
                      INNER JOIN tbl_fcc AS fg ON fg.id_fcc = dg.id_fcc
                      WHERE (i.id_tod = '".$table."' AND fg.id_fcc = '".$id_fcc."') AND ( ";
    $i = 0;
    $len = count($leftOfParentDualities);
    foreach ($leftOfParentDualities as $lopDuality) {
      $particularFcc = $lopDuality['id_fcc'];
      if ($i < $len-1) {
        $sqlCondition5L = $sqlCondition5L."fp.id_fcc = '".$particularFcc."' OR ";
      } else if ($i == $len - 1) {
        $sqlCondition5L = $sqlCondition5L."fp.id_fcc = '".$particularFcc."' );";
      }
      $i++;
    }
    $result = mysqli_query($conn,$sqlCondition5L);
    if($result){

      if(mysqli_num_rows($result)>0){
        $condition5a = false;
        echo "condition5a = false : There are inclusions.<br>";
      } else if (mysqli_num_rows($result) == 0){
        $condition5a = true;
        echo "condition5a = true : No inclusions.<br>";
      }
    }

    // right side (the parent is in the left)
    $sqlCondition5R = "SELECT id_inclusion FROM tbl_inclusion AS i 
                      INNER JOIN tbl_dynamism AS dp ON dp.id_dynamism = i.id_particular 
                      INNER JOIN tbl_dynamism AS dg ON dg.id_dynamism = i.id_general 
                      INNER JOIN tbl_fcc AS fp ON fp.id_fcc = dp.id_fcc
                      INNER JOIN tbl_fcc AS fg ON fg.id_fcc = dg.id_fcc
                      WHERE (i.id_tod = '".$table."' AND fp.id_fcc = '".$id_fcc."') AND ( ";
    $i = 0;
    $len = count($rightOfParentDualities);
    foreach ($rightOfParentDualities as $ropDuality) {
      $generalFcc = $ropDuality['id_fcc'];
      if ($i < $len-1) {
        $sqlCondition5R = $sqlCondition5R."fg.id_fcc = '".$generalFcc."' OR ";
      } else if ($i == $len - 1) {
        $sqlCondition5R = $sqlCondition5R."fg.id_fcc = '".$generalFcc."' );";
      }
      $i++;
    }

    $result = mysqli_query($conn,$sqlCondition5R);
    if(mysqli_num_rows($result)>0){
      $condition5b = false;
      echo "condition5b = false : There are inclusions.<br>";
    } else if (mysqli_num_rows($result) == 0){
      $condition5b = true;
      echo "condition5b = true : No inclusions.<br>";
    }

    if(isset($condition5a) && isset($condition5b)){
      if(!$condition5a || !$condition5b){
        // there's one inclusion, abort.
        echo "aborting.<br>";
        $condition5 = false;
      } else if($condition5a && $condition5b){
        // remove
        $condition5 = true;
      } 
    }
  }

  // echo "Variable test is being declared... ";
  // $test;
  // echo "<br>is it set? ";
  // echo isset($test) ? "true" : "false";
  // echo "<br>so it shouldn't be true or false, which is it?: ";
  // echo $test ? "true" : "false";
  // echo "<br> now let's assign the false value...";
  // $test = false;
  // echo "<br> it still gives false (value:";
  // echo $test ? "true" : "false";
  // echo ")<br> but now the isset gives true (value:";
  // echo isset($test) ? "true" : "false";
  // echo ")";

  // condition6: check if it is the last FCC of the branch
  //              i.e. if its branch has only one subBranch
  //    yes: check condition 4
  //    no: check condition4
  // remove
  if(isset($condition1) && $condition1 && isset($condition4) && ((isset($condition5) && $condition5) || (!isset($condition5)))){
    $sqlcondition6 = "SELECT c2d.id_container_2 FROM tbl_container_2 AS c2d 
                      INNER JOIN tbl_container_1 AS c1d ON c1d.id_container_1=c2d.id_container_1
                      INNER JOIN tbl_container_2 AS c2o ON c2o.id_container_1 = c1d.id_container_1
                      WHERE c2o.id_container_2 = '".$id_container_2."'";
    $result = mysqli_query($conn,$sqlcondition6);
    if(mysqli_num_rows($result)==1){
      $condition6 = false;
      echo 'condition6 = false : it is the last container2.<br>';
    } else if (mysqli_num_rows($result)>1){
      $condition6 = true;
      echo 'condition6 = true : there are more than 1 container2s.<br>';
    }
  }
      
  if(isset($condition6) && $condition6){
    // delete only subbranches' trunks
    $sqlDelete = "DELETE c1s, c0s, c0i2, c2 FROM tbl_container_1 AS c1
    INNER JOIN tbl_container_2 AS c2 ON c2.id_container_1 = c1.id_container_1
    INNER JOIN tbl_container_0_in_2 AS c0i2 ON c0i2.id_container_2 = c2.id_container_2
    INNER JOIN tbl_container_1 AS c1s ON c1s.id_container_0 = c0i2.id_container_0
    INNER JOIN tbl_container_0 AS c0s ON c0s.id_container_0 = c0i2.id_container_0
    INNER JOIN tbl_fcc AS f ON f.id_fcc = c2.id_fcc
    WHERE f.id_fcc = '".$id_fcc."'";
    if(!mysqli_query($conn,$sqlDelete)){
      echo "Error2";
    } else {
      echo "Duality removed.";
      header("refresh:0;url=tables.php?id=".$table."&option=Open");
    }
  } else if (isset($condition6) && !$condition6){
    // delete subbranches and branches' trunks
    $sqlDelete = "DELETE c1b, c1s, c0b, c0s, c0i1, c0i2, c2 FROM tbl_container_1 AS c1
    INNER JOIN tbl_container_0_in_1 AS c0i1 ON c0i1.id_container_1 = c1.id_container_1
    INNER JOIN tbl_container_0 AS c0b ON c0b.id_container_0 = c0i1.id_container_0  
    INNER JOIN tbl_container_1 AS c1b ON c1b.id_container_0 = c0b.id_container_0
    INNER JOIN tbl_container_2 AS c2 ON c2.id_container_1 = c1.id_container_1
    INNER JOIN tbl_container_0_in_2 AS c0i2 ON c0i2.id_container_2 = c2.id_container_2
    INNER JOIN tbl_container_1 AS c1s ON c1s.id_container_0 = c0i2.id_container_0
    INNER JOIN tbl_container_0 AS c0s ON c0s.id_container_0 = c0i2.id_container_0
    INNER JOIN tbl_fcc AS f ON f.id_fcc = c2.id_fcc
    WHERE f.id_fcc = '".$id_fcc."'";
    if(!mysqli_query($conn,$sqlDelete)){
      echo "error1";
    } else {
      echo "Duality removed.";
      header("refresh:0;url=tables.php?id=".$table."&option=Open");
    }
  }
  if(!isset($condition6)){
    $location = "tables.php?id=".$table."&option=Open";
    echo "Refreshing the page in 5 seconds...";
    header("refresh:5;url=tables.php?id=".$table."&option=Open");
  }
  ?>