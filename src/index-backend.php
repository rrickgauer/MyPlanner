<?php

session_start();
include('functions.php');


// get the projects
if (isset($_GET['function'], $_GET['query']) && $_GET['function'] == 'get-projects') {
  $userID = $_SESSION['userID'];
  $query = $_GET['query'];
  $projects = getProjects($userID, $query)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($projects);
  exit;
}





































?>