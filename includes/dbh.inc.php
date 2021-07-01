<?php

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
    die(mysqli_connect_error);
  }
