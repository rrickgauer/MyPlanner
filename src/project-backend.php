<?php

session_start();
include('functions.php');


// insert a new checklist item
if (isset($_POST['checklistID']) && isset($_POST['content'])) {

  // insert the new checklist item
  insertProjectChecklistItem($_POST['checklistID'], $_POST['content']);

  // retrieve the updated list
  $items = getProjectChecklistItems($_POST['checklistID'])->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);

  exit;
}


// return a project checklist items
else if (isset($_GET['checklistID']) && isset($_GET['data']) && $_GET['data'] == 'items') {
  $checklistID = $_GET['checklistID'];
  $items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}

// create a new project checklist
else if (isset($_POST['projectID']) && isset($_POST['name'])) {
  insertProjectChecklist($_POST['projectID'], $_POST['name']);
  $checklists = getProjectChecklists($_POST['projectID'])->fetchAll(PDO::FETCH_ASSOC);
  $response = json_encode($checklists);
  echo $response;
  exit;
}

// return all project's checklist data
else if (isset($_GET['projectID'])) {
  $checklists = getProjectChecklists($_GET['projectID'])->fetchAll(PDO::FETCH_ASSOC);
  $response = json_encode($checklists);
  echo $response;
  exit;
}


else if (isset($_POST['checklistID']) && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['projectID'])) {

  $checklistID = $_POST['checklistID'];
  $projectID = $_POST['projectID'];

  // delete checklist
  deleteProjectChecklist($checklistID);
  $checklists = getProjectChecklists($projectID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($checklists);
  exit;
}

else if (isset($_POST['projectChecklistItemID']) && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['checklistID'])) {
  // get the variables
  $projectChecklistItemID = $_POST['projectChecklistItemID'];
  $checklistID = $_POST['checklistID'];

  // delete the project checklist item
  deleteProjectChecklistItem($projectChecklistItemID);

  // retrieve the new list of items
  $items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}

else if (isset($_POST['projectChecklistItemID']) && isset($_POST['action']) && isset($_POST['checklistID']) && $_POST['action'] == 'incomplete' ) {
  // get the variables
  $projectChecklistItemID = $_POST['projectChecklistItemID'];
  $checklistID = $_POST['checklistID'];

  // delete the project checklist item
  setProjectChecklistItemIncomplete($projectChecklistItemID);

  // retrieve the new list of items
  $items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}

else if (isset($_POST['projectChecklistItemID']) && isset($_POST['action']) && isset($_POST['checklistID']) && $_POST['action'] == 'complete' ) {
  // get the variables
  $projectChecklistItemID = $_POST['projectChecklistItemID'];
  $checklistID = $_POST['checklistID'];

  // delete the project checklist item
  setProjectChecklistItemComplete($projectChecklistItemID);

  // retrieve the new list of items
  $items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($items);
  exit;
}





















?>