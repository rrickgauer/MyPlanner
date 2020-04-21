<?php
include('functions.php');
include('session.php');
$user = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
   <?php include('header.php'); ?>
   <title>Home</title>
</head>
<body>

   <?php include('navbar.php'); ?>

   <div class="container">
      <h1>My Planner</h1>
   </div>


   <?php include('footer.php'); ?>
</body>
</html>
