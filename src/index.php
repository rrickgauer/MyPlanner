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
  <h1 class="text-center custom-font mt-5">My Planner</h1>
  
  <!-- project selection -->
  <div id="projects">
    <h5 class="mb-3 mt-3">Your projects</h5>

    <!-- project cards go here -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
      
      <!-- add new project link -->
      <div class="col">
        <a class="btn btn-lg btn-primary" href="new-project.php" id="new-project-btn">New project</a>
      </div>
    </div>
  </div>


</div>

<?php include('footer.php'); ?>
<script src="index-js.js"></script>



</body>
</html>
