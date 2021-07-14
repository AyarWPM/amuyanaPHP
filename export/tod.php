<html>
<head>
<title>Exported Table of Deductions</title>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="../includes/jquery-ui-1.12.1.custom/jquery-ui.css">
<script src="../jquery-3.6.0.js"></script>
<script src="../includes/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="script.js"></script>
</head>
<body style="background:#eeeeee;">
<div class="todContainer">
<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  // global variables declarations
  $dynArray = array();
  $dynArrayEncoded;
  $container0sAggregated=array();
  $container0sArrayEncoded;
  $container2sAggregated=array();
  $container2sArrayEncoded;
  $container1sAggregated=array();
  $container1sArrayEncoded;
  $dbServername = "localhost";
  $dbUsername = "amuyana";
  $dbPassword = "prharcopos";
  $dbName = "amuyana";
  // $dbServername = "amuyana.net";
  // $dbUsername = "coxpueqo_client";
  // $dbPassword = "coxpueqo_password";
  // $dbName = "coxpueqo_amuyana";
  $conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
  if(!$conn) {
    exit("Error connecting to the database.");
  }
  $currentTable = $_GET["id"];
  if($currentTable===null){
    exit("Nothing to show here.");
  }
  if(!is_numeric($currentTable)){
    exit("Wrong id.");
  }
  $conn;
  // check that table exists
  $sql = "SELECT id_tod FROM tbl_tod WHERE tbl_tod.id_tod = $currentTable";
  $result = mysqli_query($conn,$sql);
  if(!$result){
    exit("Error fetching results.");
  } else {
    if(mysqli_num_rows($result) === 0){
      exit("Table doesn't exist.");
    } else if (mysqli_num_rows($result) === 1){
      // Table exists
      getContent();
    }
  }

  function getContent(){
    // fetch id_container_0 from database
    global $conn;
    global $currentTable;
    $id_container_0;
    $sql = "SELECT id_container_0 FROM tbl_tod WHERE tbl_tod.id_tod = $currentTable;";
    $result = mysqli_query($conn,$sql);
    if(!$result){
      exit("Error fetching results c0.");
    } else {
      while($row=mysqli_fetch_assoc($result)){
        $id_container_0 = $row["id_container_0"];
      }
    }
    ?>
    
    <div class="zoom">
    <input type="button" class="zoomButton" value="+" onclick="zoomIn()">
    <input type="button" class="zoomButton" value="-" onclick="zoomOut()">
    </div>
    <div class="canvas" id="canvas">
    <div id="tree" class="tree">
    <?php
    getTrunk($id_container_0,0);
    ?>
    </div>
    </div>
    <?php
    setDataJS();
  }

  function getTrunk($id_container_0, $side){
    global $conn;
    global $container0sAggregated;
    global $container0sArrayEncoded;
    global $container1sAggregated;
    global $container1sArrayEncoded;
    global $container2sAggregated;
    global $container2sArrayEncoded;

    echo ($side==-1) ? '<div class="trunk leftTrunk">' : (($side==0) ? '<div class="trunk rootTrunk">' : (($side==1) ? '<div class="trunk rightTrunk">' : ''));
    $sql = "SELECT id_container_1, branch_order 
    FROM tbl_container_1 WHERE id_container_0='".$id_container_0."' ORDER BY branch_order;";
    $result = mysqli_query($conn,$sql);
    if(!$result){
      exit("Error selecting branch.");
    } else {
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          getBranch($row['id_container_1']);
        }
      }
    }
    echo '</div>';
  }

  function getBranch($id_branch){
    global $conn;
    // if this branch doesnt have subBranches, dont display it, even if there are containers0in1 and a container1 inside that trunk
    $sqlTest = "SELECT id_container_2, id_fcc, sub_branch_order
    FROM tbl_container_2 WHERE tbl_container_2.id_container_1='".$id_branch."' ORDER BY sub_branch_order ASC;";
    $result = mysqli_query($conn,$sqlTest);
    if(!$result){
      exit("Error testing.");
    } else {
      if(mysqli_num_rows($result) === 0){
        return;
      }
    }
    $id_branch_div = "branch".$id_branch;
    echo '<div class="branch" id="'.$id_branch_div.'">';
    //checking left and right trunks
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
  }

  function getSubbranch($id_subBranch, $id_fcc){
    echo '<div class="subBranch">';
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
    echo '<div id="dual'.$id_fcc.'" class="fruitName" title="'.$description.'" >'.$name.'</div><div class="formulation"> ('.$element.'&middot;'.$antiElement.')</div>';
    echo '</div>';
    echo '<div class="fruitBracket">';
    echo '<img src="bracket.png" alt="bracket">';
    echo '</div>';
    echo '<div class="fruitFormulation">';
    echo '<div id="'.$divIdPositiveDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionPositive.'" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$positive.'</div>';
    echo '<div id="'.$divIdNegativeDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionNegative.'" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$negative.'</div>';
    echo '<div id="'.$divIdSymmetricDyn.'" onmouseover="mouseOverDynamism(this.id)" title="'.$descriptionSymmetric.'" title="'.$descriptionPositive.'" onmouseleave="mouseLeaveDynamism()" class="proposition">'.$symmetric.'</div>';
    echo '</div>';
    echo '<div class="fruitConnector">';
    echo '</div>';
    echo '</div>'; 
  }

  function setDataJS(){
    global $conn;
    global $currentTable;
    // get data of inclusions, then encode
    $sql = "SELECT id_particular, id_general FROM tbl_inclusion
    WHERE id_tod=".$currentTable.";";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    if($result){
      if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
          $datas[] = $row;
        }
      }
    }
    //encoding
    // $container0sArrayEncoded = json_encode($container0sAggregated);
    global $dynArray;
    $dynArrayEncoded = json_encode($dynArray);
    $inclusionsArrayEncoded = json_encode($datas);

    // echo '<div id="container0sArrayDiv" style="display:none;">'.$this->container0sArrayEncoded.'</div>';
    // echo '<div id="container1sArrayDiv" style="display:none;">'.$this->container1sArrayEncoded.'</div>';
    // echo '<div id="container2sArrayDiv" style="display:none;">'.$this->container2sArrayEncoded.'</div>';
    echo '<div id="dynamismsArrayDiv" style="display:none;">'.$dynArrayEncoded.'</div>';
    echo '<div id="inclusionsArrayDiv" style="display:none;">'.$inclusionsArrayEncoded.'</div>';    
    echo '<script>setLists();</script>';
  }
  
  
?>
<!-- close div class table -->
</div> 
</body>
</html>