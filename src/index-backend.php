<?php

session_start();
include('functions.php');


// get the projects
if (isset($_GET['function'], $_GET['query'], $_GET['sort']) && $_GET['function'] == 'get-projects') {
  $userID = $_SESSION['userID'];
  $query = $_GET['query'];
  $sort = $_GET['sort'];


  switch ($sort) {
    case 'name_desc':
      $projects = getProjects($userID, 'Projects.name DESC', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
    case 'date_created_new':
      $projects = getProjects($userID, 'Projects.date_created DESC', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
    case 'date_created_old':
      $projects = getProjects($userID, 'Projects.date_created ASC', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
    case 'date_due_new':
      $projects = getProjects($userID, 'Projects.date_due asc', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
    case 'date_due_old':
      $projects = getProjects($userID, 'Projects.date_due DESC', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
    default:
      $projects = getProjects($userID, 'Projects.name ASC', $query)->fetchAll(PDO::FETCH_ASSOC);
      break;
  }


  echo json_encode($projects);
  exit;
}





































?>