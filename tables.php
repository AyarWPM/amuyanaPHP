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

        $dynArrayEncoded = json_encode($dynArray);
        echo '<div id="dynamismsArrayDiv" style="display:none;">'.$dynArrayEncoded.'</div>';

        // get data of inclusions, encode
        $sql = "SELECT id_particular, id_general FROM tbl_inclusion
        WHERE id_tod= $currentTable;";
        $result = mysqli_query($conn,$sql);
        $datas=array();
        if(mysqli_num_rows($result) > 0){
          while($row=mysqli_fetch_assoc($result)){
            $datas[] = $row;
          }
        }
        $inclusionsArrayEncoded = json_encode($datas);
        echo '<div id="inclusionsArrayDiv" style="display:none;">'.$inclusionsArrayEncoded.'</div>';
      ?>
      <script>
        listDynamisms = JSON.parse(document.getElementById('dynamismsArrayDiv').innerHTML);
        listInclusions = JSON.parse(document.getElementById('inclusionsArrayDiv').innerHTML);
      </script>
    </div>
  </div>
<?php include 'includes/footer.php'?>
