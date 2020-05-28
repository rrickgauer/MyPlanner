<?php

function dbConnect() {
  include('db-info.php');

  try {
    // connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbName",$user,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;

  } catch(PDOexception $e) {
      return 0;
  }
}

function echoResponse($data) {
  $resonse = json_decode($data);
  echo $response;
  exit;
}

function insertUser($email, $password) {
   // connect to db
   $pdo = dbConnect();

   // prepare sql statement
   $sql = $pdo->prepare('INSERT INTO Users (email, password) VALUES (:email, :password)');

   // filter, sanitize, and hash email and password
   $email    = filter_var($email, FILTER_SANITIZE_EMAIL);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $hashPassword = password_hash($password, PASSWORD_DEFAULT);

   // bind parameters to the prepared sql statement
   $sql->bindParam(':email', $email, PDO::PARAM_STR);
   $sql->bindParam(':password', $hashPassword, PDO::PARAM_STR);

   // execute statement and close the connections
   $sql->execute();
   $sql = null;
   $pdo = null;
}

function validateLogin($email, $password) {
   $pdo = dbConnect();
   $sql = $pdo->prepare('SELECT password FROM Users WHERE email=:email LIMIT 1');

   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $sql->bindParam(':email', $email, PDO::PARAM_STR);
   $sql->execute();

   $userPassword = $sql->fetch(PDO::FETCH_ASSOC);
   $hash = $userPassword['password'];

   return password_verify($password, $hash);

}

function getUserID($email) {
   $pdo = dbConnect();
   $sql = $pdo->prepare('SELECT id FROM Users WHERE email=:email LIMIT 1');

   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $sql->bindParam(':email', $email, PDO::PARAM_STR);
   $sql->execute();

   $row = $sql->fetch(PDO::FETCH_ASSOC);
   return $row['id'];
}

function getUserInfo($id) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT * FROM Users WHERE id=:id LIMIT 1');
  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':id', $id, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function updateUserEmail($userID, $email) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Users SET email=:email WHERE id=:userID');

  $email  = filter_var($email, FILTER_SANITIZE_EMAIL);
  $sql->bindParam(':email', $email, PDO::PARAM_STR);

  $userID  = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function updateUserPassword($userID, $password) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Users SET password=:password WHERE id=:userID');

  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $hashPassword = password_hash($password, PASSWORD_DEFAULT);
  $sql->bindParam(':password', $hashPassword, PDO::PARAM_STR);

  $userID  = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);
  $sql->execute();
}



function insertProject($userID, $name, $description = NULL, $dateDue = NULL, $dateCreated = NULL, $displayIndex = NULL) {
  $pdo = dbConnect();

  // check if date created is null
  if ($dateCreated != NULL) {
    $sql = $pdo->prepare('INSERT INTO Projects (user_id, name, description, date_due, date_created, display_index) VALUES (:user_id, :name, :description, :date_due, :date_created, :display_index)');
    $dateCreated  = filter_var($dateCreated, FILTER_SANITIZE_STRING);
    $sql->bindParam(':date_created', $dateCreated, PDO::PARAM_STR);

  } else {
    $sql = $pdo->prepare('INSERT INTO Projects (user_id, name, description, date_due, date_created, display_index) VALUES (:user_id, :name, :description, :date_due, NOW(), :display_index)');
  }


  // filter and sanitize variables
  $userID       = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $name         = filter_var($name, FILTER_SANITIZE_STRING);
  $displayIndex = getProjectCount($userID) + 1;   // need to update later
  $description = setProjectValueNull($description);
  $dateDue = setProjectValueNull($dateDue);

  // bind parameters
  $sql->bindParam(':user_id', $userID, PDO::PARAM_INT);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);
  $sql->bindParam(':description', $description, PDO::PARAM_STR);
  $sql->bindParam(':date_due', $dateDue, PDO::PARAM_STR);
  $sql->bindParam(':display_index', $displayIndex, PDO::PARAM_INT);

  // execute statement
  $sql->execute();

  // close connections
  $pdo = NULL;
  $sql = NULL;

}


// returns the id of the most recently created project by a user
function getLastCreatedProjectID($userID) {

  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT id FROM Projects WHERE user_id=:userID ORDER BY id DESC LIMIT 1');
  $userID = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['id'];
}

// get the number of projects a user has
function getProjectCount($userID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) as count FROM Projects WHERE user_id=:userID');
  $userID = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}

// if length of paramter is 0 then set it to null 
function setProjectValueNull($value) {
  if (strlen($value) > 0) 
    return filter_var($value, FILTER_SANITIZE_STRING);
  else 
    return NULL;
}



/************************************************
* returns:

* id
* name
* date_created
* date_due
* date_due_display_date
* date_due_display_time
* display_index
* count_checklists
* count_items
* count_notes
************************************************/
function getProjects($userID, $sort, $query = '') {
  $pdo = dbConnect();

  // $stmt = 'SELECT Projects.id, Projects.name, Projects.date_created, Projects.date_due, DATE_FORMAT(Projects.date_due, "%c-%d-%Y") AS date_due_display_date, DATE_FORMAT(Projects.date_due, "%l:%i %p") AS date_due_display_time, Projects.display_index, (SELECT COUNT(Project_Checklists.id) FROM Project_Checklists WHERE Project_Checklists.project_id=Projects.id) AS count_checklists, (SELECT COUNT(Items.id) FROM Items WHERE Items.project_id=Projects.id) AS count_items, (SELECT COUNT(Project_Notes.id) FROM Project_Notes WHERE Project_Notes.project_id=Projects.id) AS count_notes FROM Projects WHERE Projects.user_id=:userID AND Projects.name like :name GROUP BY Projects.id ORDER BY ' . $sort;

  $stmt = 'SELECT Projects.id, Projects.name, Projects.date_created, DATE_FORMAT(Projects.date_created, "%c/%d/%y") AS date_created_display_date, DATE_FORMAT(Projects.date_created, "%l:%i %p") AS date_created_display_time, Projects.date_due, DATE_FORMAT(Projects.date_due, "%c/%d/%y") AS date_due_display_date, DATE_FORMAT(Projects.date_due, "%l:%i %p") AS date_due_display_time, Projects.display_index, (SELECT COUNT(Project_Checklists.id) FROM Project_Checklists WHERE Project_Checklists.project_id=Projects.id) AS count_checklists, (SELECT COUNT(Items.id) FROM Items WHERE Items.project_id=Projects.id) AS count_items, (SELECT COUNT(Project_Notes.id) FROM Project_Notes WHERE Project_Notes.project_id=Projects.id) AS count_notes FROM Projects WHERE Projects.user_id=:userID AND Projects.name like :name GROUP BY Projects.id ORDER BY ' . $sort;



  $sql = $pdo->prepare($stmt);

  $userID = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);


  $name = "%$query%";
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);



  $sql->execute();

  return $sql;
}

function getProjectCard($id, $name, $dateDue, $time) {
  echo '<div class="col"><div class="card card-project" data-project-id="' . $id . '"><div class="card-body"><h5 class="card-title">';
  echo $name;
  echo '</h5></div><div class="card-footer">';
  echo "<div class=\"card-project-date\">$dateDue $time</div>";
  echo "<a href=\"project.php?projectID=$id\">View</a>";
  echo '</div></div></div>';
}

function getProjectInfo($id) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Projects.name, Projects.description, date_format(Projects.date_created, "%c-%d-%Y") AS date_created, date_format(Projects.date_created, "%l:%i %p") AS date_created_time, date_format(Projects.date_due, "%c-%d-%Y") AS date_due, date_format(Projects.date_due, "%l:%i %p") AS date_due_time FROM Projects WHERE id=:id GROUP BY Projects.id');

  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':id', $id, PDO::PARAM_INT);

  $sql->execute();

  return $sql;
}

function insertProjectChecklist($projectID, $name) {

  $pdo = dbConnect();

  $sql = $pdo->prepare('INSERT INTO Project_Checklists (project_id, name, display_index) VALUES (:projectID, :name, :displayIndex)');

  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);

  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);


  $displayIndex = getProjectChecklistCount($projectID) + 1;
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);


  $sql->execute();

  $sql = null;
  $pdo = null;

}


function getProjectChecklistCount($projectID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) as count FROM Project_Checklists WHERE project_id=:projectID');
  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);
  $sql->execute();
  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}


function getProjectChecklists($projectID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Project_Checklists.id, Project_Checklists.name, Project_Checklists.display_index, COUNT(Project_Checklist_Items.id) as itemCount FROM Project_Checklists LEFT JOIN Project_Checklist_Items ON Project_Checklists.id=Project_Checklist_Items.project_checklist_id WHERE project_id=:projectID GROUP BY Project_Checklists.id');
  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);
  $sql->execute();

  return $sql;
}

function insertProjectChecklistItem($projectChecklistID, $content, $completed = 'n') {

  $pdo = dbConnect();
  $sql = $pdo->prepare('INSERT INTO Project_Checklist_Items (project_checklist_id, content, completed, display_index) VALUES (:projectChecklistID, :content, :completed, :displayIndex)');


  $projectChecklistID = filter_var($projectChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistID', $projectChecklistID, PDO::PARAM_INT);

  $content = filter_var($content, FILTER_SANITIZE_STRING);
  $sql->bindParam(':content', $content, PDO::PARAM_STR);

  $completed = filter_var($completed, FILTER_SANITIZE_STRING);
  $sql->bindParam(':completed', $completed, PDO::PARAM_STR);


  $displayIndex = getProjectChecklistItemsCount($projectChecklistID) + 1;
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);

  $sql->execute();

  $pdo = null;
  $sql = null;


}

function getProjectChecklistItemsCount($projectChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) as count FROM Project_Checklist_Items WHERE project_checklist_id=:projectChecklistID');

  $projectChecklistID = filter_var($projectChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistID', $projectChecklistID, PDO::PARAM_INT);

  $sql->execute();
  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}


function getProjectChecklistItems($projectChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT * FROM Project_Checklist_Items WHERE project_checklist_id=:projectChecklistID');

  $projectChecklistID = filter_var($projectChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistID', $projectChecklistID, PDO::PARAM_INT);

  $sql->execute();

  return $sql;
}

function deleteProjectChecklist($projectChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Project_Checklists WHERE id=:projectChecklistID');
  $projectChecklistID = filter_var($projectChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistID', $projectChecklistID, PDO::PARAM_INT);
  $sql->execute();
}


function deleteProjectChecklistItem($projectChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Project_Checklist_Items WHERE id=:projectChecklistItemID');
  $projectChecklistItemID = filter_var($projectChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistItemID', $projectChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

function setProjectChecklistItemComplete($projectChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Project_Checklist_Items SET completed="y" WHERE id=:projectChecklistItemID');
  $projectChecklistItemID = filter_var($projectChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistItemID', $projectChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

function setProjectChecklistItemIncomplete($projectChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Project_Checklist_Items SET completed="n" WHERE id=:projectChecklistItemID');
  $projectChecklistItemID = filter_var($projectChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectChecklistItemID', $projectChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

function updateProjectName($projectID, $name) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Projects SET name=:name WHERE id=:projectID');


  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);

  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);

  $sql->execute();
}

function deleteProject($projectID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Projects WHERE id=:projectID');
  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);
  $sql->execute();
}

function insertItem($projectID, $name, $description) {

  $pdo = dbConnect();
  $sql = $pdo->prepare('INSERT INTO Items (project_id, name, description, date_created, display_index) VALUES (:projectID, :name, :description, NOW(), :displayIndex)');

  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);

  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);

  $description = filter_var($description, FILTER_SANITIZE_STRING);
  $sql->bindParam(':description', $description, PDO::PARAM_STR);

  $displayIndex = getProjectItemCount($projectID) + 1;
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);

  $sql->execute();

  $sql = NULL;
  $pdo = NULL;
}

function getProjectItemCount($projectID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) AS count FROM Items WHERE project_id=:projectID');
  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}


/******************************************************************
  returns:
  
    id
    name
    date_created
    date_due
    completed
    display_index
    count_checklists
    count_notes
    date_due_date
    date_created_date
    date_created_time
********************************************************************/
function getProjectItems($projectID, $sort, $query = '') {
  $pdo = dbConnect();

  $stmt = 'SELECT Items.id as id, Items.name, Items.date_created, Items.date_due, Items.completed, Items.display_index, (select count(Item_Checklists.id) from Item_Checklists where Item_Checklists.item_id=Items.id) as count_checklists, (select count(Item_Notes.id) from Item_Notes where Item_Notes.item_id=Items.id) as count_notes, date_format(Items.date_due, "%c/%e/%Y") as date_due_date, date_format(Items.date_created, "%c/%e/%Y") as date_created_date, date_format(Items.date_created, "%l:%i%p") as date_created_time from Items LEFT join Item_Checklists on Items.id=Item_Checklists.item_id LEFT JOIN Item_Notes on Items.id=Item_Notes.item_id WHERE Items.project_id=:projectID AND Items.name like :name GROUP by Items.id ORDER BY ' . $sort;

  $sql = $pdo->prepare($stmt);

  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);

  $name = "%$query%";
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);

  $sql->execute();
  return $sql;
}


function getMostRecentItem($projectID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT * FROM Items WHERE project_id=:projectID ORDER BY id DESC LIMIT 1');
  $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':projectID', $projectID, PDO::PARAM_INT);
  $sql->execute();
  
  return $sql;
}

function getProjectItem($itemID) {
  $pdo = dbConnect($itemID);
  $sql = $pdo->prepare('SELECT Items.id, Items.name, Items.name, Items.description, Items.completed, Items.date_created, Items.date_due, date_format(Items.date_due, "%c/%e/%Y") as date_due_date, date_format(Items.date_created, "%c/%e/%Y") as date_created_date, date_format(Items.date_created, "%l:%i%p") as date_created_time FROM Items WHERE id=:itemID LIMIT 1');
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function insertItemChecklist($itemID, $name) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('INSERT INTO Item_Checklists (item_id, name, display_index) VALUES (:itemID, :name, :displayIndex)');

  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);

  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);

  $displayIndex = getItemChecklistCount($itemID) + 1;
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);

  $sql->execute();

}

function getItemChecklistCount($itemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(Item_Checklists.id) as count FROM Item_Checklists WHERE item_id=:itemID LIMIT 1');
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}

function getItemChecklists($itemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Item_Checklists.id, Item_Checklists.name, Item_Checklists.display_index, Item_Checklists.open FROM Item_Checklists WHERE Item_Checklists.item_id=:itemID ORDER BY Item_Checklists.display_index ASC');
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $sql->execute();

  return $sql;
}

function getItemChecklistItems($itemChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Item_Checklist_Items.id, Item_Checklist_Items.item_checklist_id, Item_Checklist_Items.content, Item_Checklist_Items.completed, Item_Checklist_Items.display_index FROM Item_Checklist_Items WHERE Item_Checklist_Items.item_checklist_id=:itemChecklistID ORDER BY Item_Checklist_Items.display_index ASC');
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function getItemChecklist($itemChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT * FROM Item_Checklists WHERE id=:itemChecklistID LIMIT 1');
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}

function updateItemChecklistOpen($itemChecklistID, $open = 'n') {
  
  if ($open == 'n' || $open == 'y') {
    $pdo = dbConnect();
    $sql = $pdo->prepare('UPDATE Item_Checklists SET open=:open WHERE id=:itemChecklistID');

    $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
    $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
    
    $open = filter_var($open, FILTER_SANITIZE_STRING);
    $sql->bindParam(':open', $open, PDO::PARAM_STR);

    $sql->execute();
  }
}

function getOpenItemChecklistIDs($itemID, $open = 'y') {
  if ($open != 'y' && $open != 'n')
    return;

  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT id FROM Item_Checklists WHERE open=:open AND item_id=:itemID ORDER BY display_index ASC');

  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  
  $open = filter_var($open, FILTER_SANITIZE_STRING);
  $sql->bindParam(':open', $open, PDO::PARAM_STR);

  $sql->execute();

  return $sql;
}

function deleteItemChecklist($itemChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Item_Checklists WHERE id=:itemChecklistID');
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
  $sql->execute();
}


// set an item checklist item to incomplete
function updateItemChecklistItemIncomplete($itemChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Item_Checklist_Items SET completed="n" WHERE id=:itemChecklistItemID');
  $itemChecklistItemID = filter_var($itemChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistItemID', $itemChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

// set an item checklist item to complete
function updateItemChecklistItemComplete($itemChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Item_Checklist_Items SET completed="y" WHERE id=:itemChecklistItemID');
  $itemChecklistItemID = filter_var($itemChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistItemID', $itemChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

// insert a new item checklist item
function insertItemChecklistItem($itemChecklistID, $content) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('INSERT INTO Item_Checklist_Items (item_checklist_id, content, display_index) VALUES (:itemChecklistID, :content, :displayIndex)');


  // sanitize item checklist id
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);

  // sanitize contnent
  $content = filter_var($content, FILTER_SANITIZE_STRING);
  $sql->bindParam(':content', $content, PDO::PARAM_STR);

  // sanitize display index
  $displayIndex = getItemChecklistItemCount($itemChecklistID) + 1;
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);

  $sql->execute();
}



function getItemChecklistItemCount($itemChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) as count FROM Item_Checklist_Items WHERE item_checklist_id=:itemChecklistID LIMIT 1');
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}

function getRecentInsertedItemChecklistItem($itemChecklistID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT * FROM Item_Checklist_Items WHERE item_checklist_id=:itemChecklistID ORDER BY id DESC LIMIT 1');
  $itemChecklistID = filter_var($itemChecklistID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistID', $itemChecklistID, PDO::PARAM_INT);
  $sql->execute();

  return $sql;

}

// delete a item checklist item
function deleteItemChecklistItem($itemChecklistItemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Item_Checklist_Items WHERE id=:itemChecklistItemID');
  $itemChecklistItemID = filter_var($itemChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistItemID', $itemChecklistItemID, PDO::PARAM_INT);
  $sql->execute();
}

// update an item checklist item's content
function updateItemChecklistItemContent($itemChecklistItemID, $content) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Item_Checklist_Items SET content=:content WHERE id=:itemChecklistItemID');

  // sanitize id
  $itemChecklistItemID = filter_var($itemChecklistItemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemChecklistItemID', $itemChecklistItemID, PDO::PARAM_INT);

  // sanitize content
  $content = filter_var($content, FILTER_SANITIZE_STRING);
  $sql->bindParam(':content', $content, PDO::PARAM_STR);

  $sql->execute();
}

// delete an item
function deleteItem($itemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Items WHERE id=:itemID');

  // sanitize id
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);

  $sql->execute();
}

function updateItem($itemID, $name, $dateDue, $dateCreated, $description) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Items SET name=:name, date_created=:dateCreated, date_due=:dateDue, description=:description WHERE id=:itemID');

  // sanitize item id
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);

  // sanitize name
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $sql->bindParam(':name', $name, PDO::PARAM_STR);

  // sanitize date created
  $dateCreated = filter_var($dateCreated, FILTER_SANITIZE_STRING);
  $sql->bindParam(':dateCreated', $dateCreated, PDO::PARAM_STR);


  // sanitize date due
  $dateDue = filter_var($dateDue, FILTER_SANITIZE_STRING);
  $sql->bindParam(':dateDue', $dateDue, PDO::PARAM_STR);

  // sanitize description
  $description = filter_var($description, FILTER_SANITIZE_STRING);
  $sql->bindParam(':description', $description, PDO::PARAM_STR);


  $sql->execute();
}


function insertItemNote($itemID, $content) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('INSERT INTO Item_Notes (item_id, content, display_index, date_created) VALUES (:itemID, :content, :displayIndex, NOW())');

  // sanitize item id
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);

  // sanitize content
  $content = filter_var($content, FILTER_SANITIZE_STRING);
  $sql->bindParam(':content', $content, PDO::PARAM_STR);

  // get and sanitize the display index
  $displayIndex = getItemNotesCount($itemID);
  $displayIndex = filter_var($displayIndex, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':displayIndex', $displayIndex, PDO::PARAM_INT);

  $sql->execute();



}

function getItemNotesCount($itemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT COUNT(id) AS count FROM Item_Notes WHERE item_id=:itemID');
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $sql->execute();

  $result = $sql->fetch(PDO::FETCH_ASSOC);
  return $result['count'];
}

function getItemNotes($itemID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Item_Notes.id, Item_Notes.content, Item_Notes.display_index, Item_Notes.date_created, date_format(Item_Notes.date_created, "%M %D, %Y at %l:%i %p") as date_created_display from Item_Notes WHERE item_id=:itemID ORDER BY id DESC');
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);
  $sql->execute();

  return $sql;
}

// get an item note by it's id
function getItemNote($itemNoteID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT Item_Notes.id, Item_Notes.content, Item_Notes.display_index, Item_Notes.date_created, date_format(Item_Notes.date_created, "%M %D, %Y at %l:%i %p") as date_created_display from Item_Notes WHERE id=:itemNoteID LIMIT 1');
  $itemNoteID = filter_var($itemNoteID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemNoteID', $itemNoteID, PDO::PARAM_INT);
  $sql->execute();
  return $sql;
}



function deleteItemNote($itemNoteID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('DELETE FROM Item_Notes WHERE id=:itemNoteID');
  $itemNoteID = filter_var($itemNoteID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemNoteID', $itemNoteID, PDO::PARAM_INT);
  $sql->execute();
}


// update an item note
function updateItemNote($itemNoteID, $content) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Item_Notes SET content=:content, date_created=NOW() WHERE id=:itemNoteID');

  // filter, sanitize, and bind the id
  $itemNoteID = filter_var($itemNoteID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemNoteID', $itemNoteID, PDO::PARAM_INT);

  // filter, sanitize, and bind the content
  $content = filter_var($content, FILTER_SANITIZE_STRING);
  $sql->bindParam(':content', $content, PDO::PARAM_STR);

  $sql->execute();
  $sql = null;
  $pdo = null;
}

// update an item's complete
function updateItemCompleted($itemID, $completed = 'y') {
  
  // if the completed argument is not y or n then exit function
  if ($completed != 'y' && $completed != 'n')
    return;

  $pdo = dbConnect();
  $sql = $pdo->prepare('UPDATE Items SET completed=:completed WHERE id=:itemID');

  // filter and bind item id
  $itemID = filter_var($itemID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':itemID', $itemID, PDO::PARAM_INT);

  // filter and bind completed
  $completed = filter_var($completed, FILTER_SANITIZE_STRING);
  $sql->bindParam(':completed', $completed, PDO::PARAM_STR);

  $sql->execute();
  $pdo = null;
  $sql = null;
}

function getAlert($message, $type = 'success') {
  $html = "<div class=\"alert alert-$type alert-dismissible fade show\" role=\"alert\">$message" . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
  return $html;
}




?>
