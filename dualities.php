<?php
  $currentPage="dualities";
  include 'includes/header.php';
  $fccs=array();
  $tods=array();
  $todHasFccs=array();
  $sql = "SELECT * FROM tbl_fcc;";
  $result = mysqli_query($conn,$sql);
  if(!$result){
    echo "mysqlError";
  } else {
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $fccs[] = $row;
      }
    }
  }

  $sql = "SELECT * FROM tbl_tod;";
  $result = mysqli_query($conn,$sql);
  if(!$result){
    echo "mysqlError";
  } else {
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $tods[] = $row;
      }
    }
  }
    

  $sql = "SELECT * FROM tbl_tod_has_fcc;";
  $result = mysqli_query($conn,$sql);
  if(!$result){
    echo "mysqlError";
  } else {
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $todHasFccs[] = $row;
      }
    }
  }
  
  echo '<div class="dualities">';
    echo '<div class="dualitiesList">';
    foreach($tods as $tod){
      echo '<div class="dualitiesItemTitle"><a href="tables.php?id='.$tod["id_tod"].'&option=Open">'.$tod["label"].'</a></div>';
      foreach($todHasFccs as $thf){
        if($thf['id_tod']==$tod['id_tod']){
          foreach($fccs as $fcc){
            if($thf['id_fcc']==$fcc['id_fcc']){
              echo '<div class="dualitiesItem"><a href="?id='.$fcc["id_fcc"].'">'.$fcc["name"].'</a></div>';
            }
          }
        }
      }
    }
    echo '<div class="dualitiesItemTitle">Deleted dualities</div>';
    //orphans
    $sql = "SELECT * FROM tbl_fcc WHERE tbl_fcc.id_fcc NOT IN 
          (SELECT tt.id_fcc FROM tbl_tod_has_fcc AS tt 
          WHERE tt.id_FCC = tbl_fcc.id_fcc);";
    $resultFccNotInTod = mysqli_query($conn,$sql);
    if($resultFccNotInTod){
    if(mysqli_num_rows($resultFccNotInTod) > 0){
    while($row=mysqli_fetch_assoc($resultFccNotInTod)){
      echo '<div class="dualitiesItem"><a href="?id='.$row["id_fcc"].'">'.$row["name"].'</a></div>';
      }
    }
    } else {
    echo "Error mysql.";
    }
    echo '</div>'; // close dualities list
    /** 
     * dualitiesData
     */
    if(empty($_GET['id'])){
      echo '<div class="dualitiesData">';
      echo 'Select a duality.';
      echo '</div>';
      echo '</div>';// closing dualities
      exit();
    }
    foreach ($fccs as $fcc) {
      if($fcc['id_fcc']==$_GET['id']){
        $name = $fcc['name'];
        $id_fcc = $fcc['id_fcc'];
        $description = $fcc['description'];
        $element = "";
        $antiElement ="";
        $sql = "SELECT symbol, polarity FROM tbl_element WHERE tbl_element.id_fcc='".$id_fcc."';";
        $result = mysqli_query($conn,$sql);
        $datas=array();
        $isResult = false;
        if(mysqli_num_rows($result) > 0){
          $isResult = true;
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        foreach($datas as $data2){
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
        echo '<div class="dualitiesData">';
        echo '<h1>'.$name.'</h1>';
        echo '<h2>id</h2>'.$id_fcc;
        echo '<h2>Algebraic notation</h2>';
        echo '<div class="formulation">'.$element.'&middot;'.$antiElement.'</div>';
        echo '<h2>Description</h2>'.$description;

        $positiveProposition ="";
        $positiveDescription = "";
        $sql = "SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '".$id_fcc."' AND tbl_dynamism.orientation = '0';";
        $result = mysqli_query($conn,$sql);
        $datas=array();
        $isResult = false;
        if(mysqli_num_rows($result) > 0){
          $isResult = true;
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        foreach($datas as $data2){
          $positiveProposition=$data2['proposition'];
          $positiveDescription=$data2['description'];
        }
        echo '<h2>Positive orientation</h2>';
        echo '<h3>Propositional expression</h3>'.$positiveProposition;
        echo '<h3>Algebraic notation</h3>';
        echo 'Contradictional conjunction:
          <div class="formulation">'
          .$element.'<div class="index">A</div>&middot;'
          .$antiElement.'<div class="index">P</div></div>
          Contradictional implication:
          <div class="formulation">'
          .$element.'<div class="index">A</div>&sup;'
          .$antiElement.'<div class="index">P</div></div>';
        echo '<h3>Description</h3>'.$positiveDescription;

        $negativeProposition = "";
        $negativeDescription ="";
        $sql = "SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '".$id_fcc."' AND tbl_dynamism.orientation = '1';";
        $result = mysqli_query($conn,$sql);
        $datas=array();
        $isResult = false;
        if(mysqli_num_rows($result) > 0){
          $isResult = true;
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        foreach($datas as $data2){
          $negativeProposition=$data2['proposition'];
          $negativeDescription=$data2['description'];
        }
        echo '<h2>Negative orientation</h2>';
        echo '<h3>Propositional expression</h3>'.$negativeProposition;
        echo '<h3>Algebraic notation</h3>';
        echo 'Contradictional conjunction:
          <div class="formulation">'
          .$antiElement.'<div class="index">A</div>&middot;'
          .$element.'<div class="index">P</div></div>
          Contradictional implication:
          <div class="formulation">'
          .$antiElement.'<div class="index">A</div>&sup;'
          .$element.'<div class="index">P</div></div>';
        echo '<h3>Description</h3>'.$negativeDescription;

        $negativeProposition = "";
        $negativeDescription ="";
        $sql = "SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '".$id_fcc."' AND tbl_dynamism.orientation = '2';";
        $result = mysqli_query($conn,$sql);
        $datas=array();
        $isResult = false;
        if(mysqli_num_rows($result) > 0){
          $isResult = true;
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        foreach($datas as $data2){
          $symmetricProposition=$data2['proposition'];
          $symmetricDescription=$data2['description'];
        }
        echo '<h2>Symmetric orientation</h2>';
        echo '<h3>Propositional expression</h3>'.$symmetricProposition;
        echo '<h3>Algebraic notation</h3>';
        echo 'Contradictional conjunction:
          <div class="formulation">'
          .$element.'<div class="index">T</div>&middot;'
          .$antiElement.'<div class="index">T</div></div>
          Contradictional implication:
          <div class="formulation">'
          .$element.'<div class="index">T</div>&sup;'
          .$antiElement.'<div class="index">T</div></div>';
        echo '<h3>Description</h3>'.$symmetricDescription;
        echo '</div>';
      }
    }
  ?>
</div>
