<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $theHost=1; // 0 = localhost , 1 = amuyana.net
  include_once 'includes/dbh.inc.php';
  include_once 'code.php';

?>

<html>
<head>
<title>Amuya&ntilde;a - Contradictory logic systems</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="includes/jquery-ui-1.12.1.custom/jquery-ui.css">
<script src="jquery-3.6.0.js"></script>
<script src="includes/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="inclusions.js"></script>
<script src="dualities.js"></script>

</head>
<body>
  <div class="stage">
    <div class="header">
      <div class="menu">
        <ul class="menuList">
          <li class="menuItem"><a class="<?php echo $currentPage == 'index' ? '' : '' ?>" href="index.php"><img id="logo" src="includes/logo.png"></a></li>
          <!-- <li class="menuItem"><a class="<?php echo $currentPage == 'systems' ? 'activeMenu' : '' ?>" href="systems.php">Logic Systems</a></li> -->
          <li class="menuItem"><a class="<?php echo $currentPage == 'tables' ? 'activeMenu' : '' ?>" href="tables.php">Table of deductions</a></li>
          <li class="menuItem"><a class="<?php echo $currentPage == 'dualities' ? 'activeMenu' : '' ?>" href="dualities.php">Dualities</a></li>
        </ul>
      </div>
    </div>
