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

  // set variables
  $itemID = $_POST['itemID'];
  $checklistName = $_POST['checklistName'];

  // insert the new checklist into the database
  insertItemChecklist($itemID, $checklistName);

  // retrieve the new item checklists data
  $itemChecklists = getItemChecklists($itemID)->fetchAll(PDO::FETCH_ASSOC);

  // return the checklists
  echo json_encode($itemChecklists);
  exit;
}

// client requests an item's checklists
else if (isset($_GET['itemID'], $_GET['function']) && $_GET['function'] == 'get-item-checklists') {
  $itemID = $_GET['itemID'];
  $checklists = getItemChecklists($itemID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($checklists);
  exit;
}

// return all the items in an item checklist
else if (isset($_GET['itemChecklistID'], $_GET['function']) && $_GET['function'] == 'get-item-checklist-items') {
  $itemChecklistID = $_GET['itemChecklistID'];


  // $itemChecklistItems = getItemChecklistItems($itemChecklistID)->fetchAll(PDO::FETCH_ASSOC);

  // $itemChecklist = getItemChecklist($itemChecklistID)->fetch(PDO::FETCH_ASSOC);

  // $data = array_merge($itemChecklist, $itemChecklistItems);

  $data = [];

  $data['Item_Checklists'] = getItemChecklist($itemChecklistID)->fetch(PDO::FETCH_ASSOC);
  $data['Item_Checklist_Items'] = getItemChecklistItems($itemChecklistID)->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($data);
  exit;
}















?>