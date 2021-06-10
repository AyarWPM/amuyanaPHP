<?php
  $currentPage="tables";
  $currentTable="1";
  // require_once 'includes/dbh.inc.php';
  include 'includes/header.php';
?>
  <div class="tables">
    <div class="select">
      <form action="" method="GET">
        <select name="id">
          <?php
            $sql = "SELECT * FROM tbl_tod;";
            $result = mysqli_query($conn,$sql);
            $datas=array();
            $isResult = false;
            $id_container_0=0;

            if(mysqli_num_rows($result) > 0){
              $isResult = true;
              while($row=mysqli_fetch_assoc($result)){
                $datas[] = $row;
              }
            }
            $currentTable = $_GET[id];
            foreach($datas as $data){
              if($data["id_tod"]==$currentTable){
                $id_container_0 = $data['id_container_0'];
                echo '<option value="'.$data["id_tod"].'" selected >'.$data["label"].'</option>';
              } else {
                echo '<option value="'.$data["id_tod"].'" >'.$data["label"].'</option>';
              }
            }
          ?>
        </select>
        <input type="submit" name="" value="Open">
      </form>
    </div>

    <div class="canvas">
      <?php
        getTree($id_container_0);
      ?>
    </div>
  </div>
<?php include 'includes/footer.php'?>
