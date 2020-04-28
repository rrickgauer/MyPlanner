<?php

session_start();
include('functions.php');


// insert new item
if (isset($_POST['function']) && $_POST['function'] == 'insert-item' && isset($_POST['projectID']) && isset($_POST['name'])) {

  $projectID = $_POST['projectID'];
  $name = $_POST['name'];
  $description = $_POST['description'];

  insertItem($projectID, $name, $description);

  echo 'success';
  exit;
}

// return all the project items
else if (isset($_GET['function']) && $_GET['function'] == 'get-items' && isset($_GET['projectID'])) {
  $projectID = $_GET['projectID'];
  $items = getProjectItems($projectID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}
?>