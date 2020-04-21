<?php
session_start();
include('functions.php');
$user = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <?php include('header.php'); ?>
      <title>My Planner</title>
   </head>
   <body>

      <div class="container">
         <h1>My Planner</h1>



         <ul>
            <li><b>ID: </b><?php echo $user['id']; ?></li>
            <li><b>Email: </b><?php echo $user['email']; ?></li>
         </ul>




      </div>


      <?php include('footer.php'); ?>
   </body>
</html>
