<?php

  $dbServername = "localhost";
  $dbUsername = "coxpueqo_freeclient";
  $dbPassword = "freeclient";
  $dbName = "coxpueqo_amuyana";

$conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
  if(!$conn) {
    die(mysqli_connect_error);
  }
