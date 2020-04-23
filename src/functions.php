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

function insertUser($email, $password) {

   // connect to db
   $pdo = dbConnect();

   // prepare sql statement
   $sql = $pdo->prepare('INSERT INTO Users (email, password) VALUES (:email, :password)');

   // filter, sanitize, and hash email and password
   $email    = filter_var($email, FILTER_SANITIZE_EMAIL);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   // $password = password_hash($password, PASSWORD_DEFAULT);

   // bind parameters to the prepared sql statement
   $sql->bindParam(':email', $email, PDO::PARAM_STR);
   $sql->bindParam(':password', $password, PDO::PARAM_STR);

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

   $storedPassword = $sql->fetch(PDO::FETCH_ASSOC);
   $storedPassword = $storedPassword['password'];

   if ($storedPassword == $password) {
      return true;
   } else {
      return false;
   }

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

function getProjects($userID) {
  $pdo = dbConnect();
  $sql = $pdo->prepare('SELECT id, name, date_due, DATE_FORMAT(date_due, "%c-%d-%Y") AS date_due_display_date, DATE_FORMAT(date_due, "%l:%i %p") AS date_due_display_time, display_index FROM Projects WHERE user_id=:userID ORDER BY display_index');
  $userID = filter_var($userID, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':userID', $userID, PDO::PARAM_INT);
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
  $sql = $pdo->prepare('SELECT name, description, date_format(date_created, "%c-%d-%Y") AS date_created, date_format(date_created, "%l:%i %p") AS date_created_time, date_format(date_due, "%c-%d-%Y") AS date_due, date_format(date_due, "%l:%i %p") AS date_due_time FROM Projects WHERE id=:id');

  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  $sql->bindParam(':id', $id, PDO::PARAM_INT);

  $sql->execute();

  return $sql;
}





?>
