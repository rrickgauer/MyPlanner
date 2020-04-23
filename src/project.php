<?php include('functions.php'); ?>
<?php include('session.php'); ?>
<?php $projectInfo = getProjectInfo($_GET['projectID'])->fetch(PDO::FETCH_ASSOC); ?>

<!DOCTYPE html>
<html>
<head>
  <?php include('header.php'); ?>
  <title><?php echo $projectInfo['name']; ?></title>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="wrapper">

    <!-- put project checklists here -->
    <nav id="sidebar">

      <div class="sidebar-header">
        <h3>Checklists</h3>
        <i class='bx bx-plus-circle'></i>
      </div>

      <ul>
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Page 2</a></li>
        <li><a href="#">Page 3</a></li>
        <li><a href="#">Page 4</a></li>      
      </ul>
    </nav>

    <div id="content">
      <button type="button" class="btn btn-primary" onclick="activateSidebar()">View checklists</button>

      <div class="project-info">

        <!-- name -->
        <h1><?php echo $projectInfo['name']; ?></h1>

        <div class="row">

          <!-- description -->  
          <div class="col-sm-12 col-md-4">
            <div class="project-info-element">
              <p class="descriptor">Description</p>
              <p class="data"><?php echo $projectInfo['description']; ?></p>          
            </div>
          </div>

          <!-- date created and due -->
          <div class="col-sm-12 col-md-4">
            <!-- date created -->
            <div class="project-info-element">
              <p class="descriptor">Created</p>
              <p class="data"><?php echo $projectInfo['date_created'] . ' ' . $projectInfo['date_created_time']; ?></p>
            </div>

            <!-- date due -->
            <div class="project-info-element">
              <p class="descriptor">Date due</p>
              <p class="data"><?php echo $projectInfo['date_due'] . ' ' . $projectInfo['date_due_time']; ?></p>
            </div>
          </div>

          <!-- notes -->
          <div class="col-sm-12 col-md-4">
            <div class="project-info-element">
              <p class="descriptor">Notes</p>
              <p class="data"></p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  
  <?php include('footer.php'); ?>

  <script>
    function activateSidebar() {
      $('#sidebar').toggleClass('active');
    }
  </script>

</body>
</html>