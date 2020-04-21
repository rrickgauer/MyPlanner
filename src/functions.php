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





?>
