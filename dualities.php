<?php
  $currentPage="dualities";
  // require_once 'includes/dbh.inc.php';
  include 'includes/header.php';
?>

<div class="dualities">
  <?php
    // global $conn;
    $sql = "SELECT * FROM tbl_fcc;";
    $result = mysqli_query($conn,$sql);
    $datas=array();
    $isResult = false;
    if(mysqli_num_rows($result) > 0){
      $isResult = true;
      while($row=mysqli_fetch_assoc($result)){
        $datas[] = $row;
      }
    }

    echo '<div class="dualitiesList">';
    foreach($datas as $data){
      echo '<div class="dualitiesItem"><a href="?id='.$data["id_fcc"].'">'.$data["name"].'</a></div>';
    }
    echo '</div>';

    foreach ($datas as $data) {
      if($data['id_fcc']==$_GET['id']){
        $name = $data['name'];
        $id_fcc = $data['id_fcc'];
        $description = $data['description'];
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

        echo '<div class="dualitiesData">';
        echo '<h1>'.$name.'</h1>';
        echo '<h2>id</h2>'.$id_fcc;
        echo '<h2>Algebraic notation</h2>';
        echo '<div class="formulation">'.$element.'&middot;'.$antiElement.'</div>';
        echo '<h2>Description</h2>'.$description;

        $positiveProposition ="";
        $positiveDescription = "";
        $sql = 'SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '.$id_fcc.' AND tbl_dynamism.orientation = 0;';
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
        $sql = 'SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '.$id_fcc.' AND tbl_dynamism.orientation = 1;';
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
        $sql = 'SELECT proposition, description FROM tbl_dynamism
        WHERE tbl_dynamism.id_fcc = '.$id_fcc.' AND tbl_dynamism.orientation = 1;';
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

<?php include 'includes/footer.php'?>
