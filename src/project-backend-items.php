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

// return project items
else if (isset($_GET['function'], $_GET['projectID'], $_GET['query']) && $_GET['function'] == 'get-items') {
  $projectID = $_GET['projectID'];
  $query = $_GET['query'];
  $items = getProjectItems($projectID, $query)->fetchAll(PDO::FETCH_ASSOC);
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
  
  // set the checklist to open
  updateItemChecklistOpen($itemChecklistID, 'y');

  $data = [];
  $data['Item_Checklists'] = getItemChecklist($itemChecklistID)->fetch(PDO::FETCH_ASSOC);
  $data['Item_Checklist_Items'] = getItemChecklistItems($itemChecklistID)->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($data);
  exit;
}

// close the item checklist
else if (isset($_POST['itemChecklistID'], $_POST['function']) && $_POST['function'] == 'close-item-checklist') {
  $itemChecklistID = $_POST['itemChecklistID'];
  updateItemChecklistOpen($itemChecklistID, 'n');
  exit;
}


// return the ids of all open item checklists in an item
else if (isset($_GET['itemID'], $_GET['function']) && $_GET['function'] == 'get-open-item-checklists') {
  $itemID = $_GET['itemID'];
  $openItemChecklists = getOpenItemChecklistIDs($itemID, 'y')->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($openItemChecklists);
  exit;
}


// delete item checklist
else if (isset($_POST['itemChecklistID'], $_POST['function']) && $_POST['function'] == 'delete-item-checklist') {
  $itemChecklistID = $_POST['itemChecklistID'];
  deleteItemChecklist($itemChecklistID);

  echo 'deleted';
}


// set item checklist to incomplete
else if (isset($_POST['itemChecklistItemID'], $_POST['function']) && $_POST['function'] == 'update-item-checklist-item-incomplete') {
  $itemChecklistItemID = $_POST['itemChecklistItemID'];
  updateItemChecklistItemIncomplete($itemChecklistItemID);
  exit;
}

// set item checklist to complete
else if (isset($_POST['itemChecklistItemID'], $_POST['function']) && $_POST['function'] == 'update-item-checklist-item-complete') {
  $itemChecklistItemID = $_POST['itemChecklistItemID'];
  updateItemChecklistItemComplete($itemChecklistItemID);
  exit;
}


// add item checklist item
else if (isset($_POST['itemChecklistID'], $_POST['function'], $_POST['content']) && $_POST['function'] == 'add-item-checklist-item') {
  $content = $_POST['content'];
  $itemChecklistID = $_POST['itemChecklistID'];

  // insert the new item
  insertItemChecklistItem($itemChecklistID, $content);

  // get the most recent item
  $newItem = getRecentInsertedItemChecklistItem($itemChecklistID)->fetch(PDO::FETCH_ASSOC);

  echo json_encode($newItem);
  exit;
}


// delete item checklist item
else if (isset($_POST['itemChecklistItemID'], $_POST['function']) && $_POST['function'] == 'delete-item-checklist-item') {
  $itemChecklistItemID = $_POST['itemChecklistItemID'];
  deleteItemChecklistItem($itemChecklistItemID);
  exit;
}

// update item checklist item content
else if (isset($_POST['function'], $_POST['itemChecklistItemID'], $_POST['content']) && $_POST['function'] == 'update-item-checklist-item-content') {
  $itemChecklistItemID = $_POST['itemChecklistItemID'];
  $content = $_POST['content'];

  updateItemChecklistItemContent($itemChecklistItemID, $content);

  $data = [];

  $data['id'] = $itemChecklistItemID;
  $data['content'] = $content;

  echo json_encode($data);
  exit;
}

// delete item
else if (isset($_POST['function'], $_POST['itemID']) && $_POST['function'] == 'delete-item') {
  $itemID = $_POST['itemID'];
  deleteItem($itemID);
  exit;
}


// update item info
else if (isset($_POST['function'], $_POST['itemID'], $_POST['name'], $_POST['dateDue'], $_POST['dateCreated'], $_POST['description']) && $_POST['function'] == 'update-item') {
  $itemID      = $_POST['itemID'];
  $name        = $_POST['name'];
  $dateDue     = $_POST['dateDue'];
  $dateCreated = $_POST['dateCreated'];
  $description = $_POST['description'];

  updateItem($itemID, $name, $dateDue, $dateCreated, $description);
  $item = getProjectItem($itemID)->fetch(PDO::FETCH_ASSOC);
  echo json_encode($item);
  exit;
}


// insert item note
else if (isset($_POST['itemID'], $_POST['content'], $_POST['function']) && $_POST['function'] == 'insert-item-note') {
  $itemID = $_POST['itemID'];
  $content = $_POST['content'];
  insertItemNote($itemID, $content);
  exit;
}


// return an item's notes
else if (isset($_GET['itemID'], $_GET['function']) && $_GET['function'] == 'get-item-notes') {
  $itemID = $_GET['itemID'];
  $itemNotes = getItemNotes($itemID)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($itemNotes);
  exit;
}


// delete an item note 
else if (isset($_POST['itemNoteID'], $_POST['function']) && $_POST['function'] == 'delete-item-note') {
  $itemNoteID = $_POST['itemNoteID'];
  deleteItemNote($itemNoteID);
  exit;
}


// update an item note
else if (isset($_POST['itemNoteID'], $_POST['function'], $_POST['content']) && $_POST['function'] == 'update-item-note') {
  $itemNoteID = $_POST['itemNoteID'];
  $content = $_POST['content'];

  // update the note
  updateItemNote($itemNoteID, $content);

  // retrieve the new note
  $updatedItemNote = getItemNote($itemNoteID)->fetch(PDO::FETCH_ASSOC);

  // return the new note
  echo json_encode($updatedItemNote);
  exit;
}

// get an item note
else if (isset($_GET['itemNoteID'], $_GET['function']) && $_GET['function'] == 'get-item-note') {
  $itemNoteID = $_GET['itemNoteID'];
  $itemNote = getItemNote($itemNoteID)->fetch(PDO::FETCH_ASSOC);
  echo json_encode($itemNote);
  exit;
}

// set item to complete
else if (isset($_POST['itemID'], $_POST['function']) && $_POST['function'] == 'update-item-to-complete') {
  $itemID = $_POST['itemID'];
  updateItemCompleted($itemID, 'y');
  exit;
}

// set item to incomplete
else if (isset($_POST['itemID'], $_POST['function']) && $_POST['function'] == 'update-item-to-incomplete') {
  $itemID = $_POST['itemID'];
  updateItemCompleted($itemID, 'n');
  exit;
}





?>