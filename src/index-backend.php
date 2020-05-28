<?php

session_start();
include('functions.php');


// send projects
if (isset($_GET['function']) && $_GET['function'] == 'get-projects') {
  $projects = getProjects($_SESSION['userID'])->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($projects);
  exit;
}





































?>