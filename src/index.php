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
    
    <!-- toolbar -->
    <div class="split toolbar">
      <div class="left">
        <h3>Your projects</h3>
      </div>
      
      <div class="right">
        <div class="d-flex">
          <div class="buttons d-flex">

            <!-- new project link -->
            <a class="btn btn-secondary mr-2" href="new-project.php">New</a>

            <!-- project sort options dropdown -->
            <div class="dropdown mr-2">
              <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">Sort</button>

              <div class="dropdown-menu">
                <h6 class="dropdown-header">Name</h6>
  
                <!-- name asc -->
                <button class="dropdown-item project-sorting-option active" type="button" data-project-sorting="name_asc">
                  <div class="icon-display"><i class='icon bx bx-sort-a-z'></i><div class="display">Ascending</div></div>
                </button>
                
                <!-- name desc -->
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="name_desc">
                  <div class="icon-display"><i class='icon bx bx-sort-z-a'></i><div class="display">Descending</div></div>
                </button>

                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Date created</h6>
                
                <!-- date created oldest -->
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_created_old">
                  <div class="icon-display"><i class='icon bx bx-sort-up'></i><div class="display">Oldest</div></div>
                </button>
                
                <!-- date created newest -->
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_created_new">
                  <div class="icon-display"><i class='icon bx bx-sort-down'></i><div class="display">Newest</div></div>
                </button>

                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Date due</h6>
                
                <!-- date due oldest -->
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_due_old">
                  <div class="icon-display"><i class='icon bx bx-sort-up'></i><div class="display">Oldest</div></div>
                </button>

                <!-- date due newest -->
                <button class="dropdown-item project-sorting-option" type="button" data-project-sorting="date_due_new">
                  <div class="icon-display"><i class='icon bx bx-sort-down'></i><div class="display">Newest</div></div>
                </button>

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
