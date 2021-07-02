<?php
    include('includes/dbh.inc.php');
    $table = $_POST['table'];
    $id_fcc = $_POST['id_fcc'];
    $container2Id = $_POST['id_container_2'];
    $name = "";
    $description = "";
    $sql = "SELECT name, description FROM tbl_fcc WHERE id_fcc='".$id_fcc."';";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
            $name= $row['name'];
            $description = $row['description'];
          }
    }
    $element = "";
    $antiElement= "";
    $sql = "SELECT symbol, polarity FROM tbl_element WHERE id_fcc='".$id_fcc."';";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
            if($row['polarity']==0){
                $element=$row['symbol'];
            } else if ($row['polarity']==1) {
                $antiElement = $row['symbol'];
            }
        }
    }

    $positiveLabel = "";
    $positiveDescription = "";
    $negativeLabel = "";
    $negativeDescription = "";
    $symmetricLabel = "";
    $symmetricDescription = "";
    $sql = "SELECT orientation, proposition, description FROM tbl_dynamism WHERE id_fcc='".$id_fcc."';";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
        while($row=mysqli_fetch_assoc($result)){
            if($row['orientation']==0){
                $positiveLabel = $row['proposition'];
                $positiveDescription = $row['description'];
            } else if($row['orientation']==1){
                $negativeLabel = $row['proposition'];
                $negativeDescription = $row['description'];
            } else if($row['orientation']==2){
                $symmetricLabel = $row['proposition'];
                $symmetricDescription = $row['description'];
            } 
        }
    }
?>

<div class="dualityEditor-header"></div>
<form id="updateForm<?php echo $id_fcc?>" action="updateDuality.php" method="post">
    <table class="dualityEditor-table">
    <input type="text" hidden="true" value="<?php echo $table?>" name="table">
        <colgroup>
            <col span="1" style="text-align:right" width="290px">
        </colgroup>
        <tr>
            <td>Unique identifier:</td>
            <td><input type="text" hidden="true" value="<?php echo $id_fcc ?>" name="id_fcc"><?php echo $id_fcc;?></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><input class="dualityEditor-input" type="text" value="<?php echo $name?>" id="dualityEditor-fccName" name="fccName" required></td>
        </tr>
        <tr>
            <td>Description:</td>
            <td><textarea class="dualityEditor-input" id="dualityEditor-fccDescription" name="fccDescription" required><?php echo $description?></textarea></td>
        </tr>
        <tr>
            <td>Element:</td>
            <td><input class="dualityEditor-input"  type="text" value="<?php echo $element ?>" id="dualityEditor-element" name="element" required></td>
        </tr>
        <tr>
            <td>Anti-element:</td>
            <td><input class="dualityEditor-input"  type="text" value="<?php echo $antiElement ?>" id="dualityEditor-anti-element" name="antiElement" required></td>
        </tr>
        <tr>
            <td>Positive orientation label:</td>
            <td><input class="dualityEditor-input" type="text" value="<?php echo $positiveLabel ?>" id="dualityEditor-positive-label" name="positiveLabel" required></td>
        </tr>
        <tr>
            <td>Positive orientation description:</td>
            <td><textarea class="dualityEditor-input"  id="dualityEditor-positive-description" name="positiveDescription" required><?php echo $positiveDescription?></textarea></td>
        </tr>
        <tr>
            <td>Negative orientation label:</td>
            <td><input class="dualityEditor-input" type="text" value="<?php echo $negativeLabel ?>" id="dualityEditor-negative-label" name="negativeLabel" required></td>
        </tr>
        <tr>
            <td>Negative orientation description:</td>
            <td><textarea class="dualityEditor-input"  id="dualityEditor-negative-description" name="negativeDescription" required><?php echo $negativeDescription?></textarea></td>
        </tr>
        <tr>
            <td>Symmetric orientation label:</td>
            <td><input class="dualityEditor-input" type="text" value="<?php echo $symmetricLabel ?>" id="dualityEditor-symmetric-label" name="symmetricLabel" required></td>
        </tr>
        <tr>
            <td>Symmetric orientation description:</td>
            <td><textarea class="dualityEditor-input"  id="dualityEditor-symmetric-description" name="symmetricDescription" required><?php echo $symmetricDescription?></textarea></td>
        </tr>
        <tr>
            <td>Algebraic formulations (?)</td>
        </tr>
        <tr>
            <td>conjunctions</td>
        </tr>
        <tr>
            <td>implications</td>
        </tr>
    </table>
    </form>
    <form id="deleteForm<?php echo $id_fcc ?>" action="removeDuality.php" method="post">
    <?php
        echo '<input type="text" hidden="true" value="'.$table.'" name="table">';
        echo '<input type="text" hidden="true" value="'.$id_fcc.'" name="id_fcc">';
        echo '<input type="text" hidden="true" value="'.$container2Id.'" name="id_container_2">';
    ?>
    </form>
    <div class="dualityEditor-button-holder">

        <input form="updateForm<?php echo $id_fcc ?>" type="submit" class="dualityEditor-button" value="Save">
        <button type="button" class="dualityEditor-button" onclick="closeDualityEditor()">Close</button>
        <button type="button" id="deleteDualityBtn" class="dualityEditor-button" onclick="confirmDelete()">Delete</button>
        <div id="confirmDeleteDuality">
            Are you sure you want to remove this duality?<br>
            (It will remain in the database for later reuse)<br>

            <input form="deleteForm<?php echo $id_fcc ?>" type="submit" class="dualityEditor-button" value="Delete">

            <button type="button<?php echo $id_fcc?>" class="dualityEditor-button" onclick="cancelDelete()">Cancel</button>
        </div>
    </div>
    
    <script>
        $("#confirmDeleteDuality").hide();
    </script>