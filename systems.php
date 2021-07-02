  <?php
    $currentPage="systems";
    include 'includes/header.php';
  ?>
  <div class="systems">
    <?php
      $sql = "SELECT * FROM tbl_logic_system;";
      $result = mysqli_query($conn,$sql);
      $datas=array();
      $isResult = false;
      if(mysqli_num_rows($result) > 0){
        $isResult = true;
        while($row=mysqli_fetch_assoc($result)){
          $datas[] = $row;
        }
      }
      echo '<div class="systemsList">';
      foreach($datas as $data){
        echo '<div class="systemsItem"><a href="?id='.$data["id_logic_system"].'">'.$data["label"].'</a></div>';
      }
      if($isResult) echo '</div>';

      foreach ($datas as $data) {
        if($data['id_logic_system']==$_GET['id']){
          echo '<div class="systemsData">';
          echo '<h2>'.$data['label'].'</h2>';
          echo '<div>id: '.$data['id_logic_system'].'</div>';
          echo '<div>Creation date: '.$data['creation_date'].'</div>';
          echo '<div>Description: '.$data['description'].'</div>';
          echo '<h3>Tables of deduction</h3>';

          $sql = "SELECT * FROM tbl_tod WHERE tbl_tod.id_logic_system=".$_GET['id']." ;";
          $result = mysqli_query($conn,$sql);
          $datas=array();
          $isResult = false;
          if(mysqli_num_rows($result) > 0){
            $isResult = true;
            while($row=mysqli_fetch_assoc($result)){
              $datas[] = $row;
            }
          }
          foreach($datas as $data){
            echo '<div><a href="tables.php?id='.$data["id_tod"].'">'.$data["label"].'</a></div>';
          }
          echo '</div>';
        }
      }
    ?>
  </div>
<?php include 'includes/footer.php'?>
