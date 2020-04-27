<?php
include('functions.php');
include('session.php');
$user = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);
$projects = getProjects($_SESSION['userID']);
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
  <h1 class="text-center">My Planner</h1>
  
  <!-- project selection -->
  <div id="projects">
    <h3>Your projects</h3>

    <!-- project cards go here -->
    <div class="row row-cols-1 row-cols-md-3">
    </div>
  </div>

</div>

<?php include('footer.php'); ?>
<script src="index-js.js"></script>



</body>
</html>
