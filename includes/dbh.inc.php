<?php

  $dbServername = "localhost";
  $dbUsername = "amuyana";
  $dbPassword = "prharcopos";
  $dbName = "amuyana";

$conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
  if(!$conn) {
    die(mysqli_connect_error);
  }
