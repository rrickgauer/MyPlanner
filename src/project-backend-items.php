<?php

session_start();
include('functions.php');


// insert new item
if (isset($_POST['function']) && $_POST['function'] == 'insert-item' && isset($_POST['projectID']) && isset($_POST['name'])) {

  $projectID = $_POST['projectID'];
  $name = $_POST['name'];
  $description = $_POST['description'];

  // insert the item
  insertItem($projectID, $name, $description);


  // get the id of this items
  $item = getMostRecentItem($projectID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($item);
  exit;
  
}

// return all the project items
else if (isset($_GET['function']) && $_GET['function'] == 'get-items' && isset($_GET['projectID'])) {
  $projectID = $_GET['projectID'];
  $items = getProjectItems($projectID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}

// return all of an item's data
else if (isset($_GET['itemID']) && isset($_GET['function']) && $_GET['function'] == 'get-item') {
  $itemID = $_GET['itemID'];
  $item = getProjectItem($itemID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($item);
  exit;
}

else if (isset($_POST['itemID'], $_POST['function'], $_POST['checklistName']) && $_POST['function'] == 'insert-item-checklist') {
  $itemID = $_POST['itemID'];
  $checklistName = $_POST['checklistName'];

  insertItemChecklist($itemID, $checklistName);


  // send all the checklists
  $itemChecklists = getItemChecklists($itemID)->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($itemChecklists);
  exit;
}
















?>