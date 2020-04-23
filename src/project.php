<?php include('functions.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html>
<head>
  <?php include('header.php'); ?>
  <title>Project</title>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="wrapper">

    <!-- put project checklists here -->
    <nav id="sidebar">
      <ul>
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Page 2</a></li>
        <li><a href="#">Page 3</a></li>
        <li><a href="#">Page 4</a></li>      
      </ul>
    </nav>

    <div id="content">
      <div class="container-fluid">
        <button type="button" class="btn btn-primary" onclick="activateSidebar()">View checklists</button>
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