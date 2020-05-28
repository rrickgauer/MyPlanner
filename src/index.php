<?php
include('functions.php');
include('session.php');
$user = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);
// $projects = getProjects($_SESSION['userID']);
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

    <div class="split toolbar">

      <div class="left">
        <h3>Your projects</h3>
      </div>
      
      <div class="right">
        <div class="d-flex">
          <div class="buttons d-flex">
            <a class="btn btn-secondary mr-2" href="new-project.php">New</a>

            <!-- project sort options dropdown -->
            <div class="dropdown mr-2">
              <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">Sort</button>

              <div class="dropdown-menu">
                <h6 class="dropdown-header">Name</h6>
                <button class="dropdown-item project-sorting-option active" type="button" data-project-sorting="name_asc">Ascending</button>
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="name_desc">Descending</button>
                <div class="dropdown-divider"></div>

                <h6 class="dropdown-header">Date created</h6>
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_created_old">Oldest</button>
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_created_new">Newest</button>
                <div class="dropdown-divider"></div>

                <h6 class="dropdown-header">Date due</h6>
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_due_old">Oldest</button>
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_due_new">Newest</button>
              </div>      
            </div>
          </div>

          <!-- project search input -->
          <input type="text" class="form-control" id="project-search-input" placeholder="Search for a project...">
        </div>
      </div>
      


    </div>

    <!-- project cards go here -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3"></div>
  </div>


</div>

<?php include('footer.php'); ?>
<script src="index-js.js"></script>



</body>
</html>
