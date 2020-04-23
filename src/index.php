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
    <div class="row row-cols-1 row-cols-md-3">
      
      <?php
      $count = 0;
      while ($project = $projects->fetch(PDO::FETCH_ASSOC)) {
            // line break
        if ($count % 3 == 0) 
          echo '</div><div class="row row-cols-1 row-cols-md-3">';
        
            // print project
        getProjectCard($project['id'], $project['name'], $project['date_due_display_date'], $project['date_due_display_time']);

        $count++;
      }
      ?>
    </div>
  </div>

</div>


<?php include('footer.php'); ?>
</body>
</html>
