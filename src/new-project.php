<?php session_start(); ?>
<?php include('functions.php'); ?>

<?php
if (isset($_POST['new-project-name'])) {
  // insert the project
  insertProject($_SESSION['userID'], $_POST['new-project-name'], $_POST['description'], $_POST['date-due']);

  // get the id of the project just created
  $projectID = getLastCreatedProjectID($_SESSION['userID']);

  // go to that projects page
  $url = 'Location: project.php?projectID=' . $projectID;
  header($url);
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
 <?php include('header.php'); ?>
 <!-- flatpickr -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 <title>New Project</title>
</head>
<body>
 <?php include('navbar.php'); ?>

 <div class="container p-5 mt-3">

  <div class="card mt-2 px-5">
    <div class="card-body">
      <h1 class="custom-font text-center ">New Project</h1> 
      
      <form method="post">
       <!-- project name -->
       <div class="form-group">
        <label for="name">Name *</label>
        <input type="text" class="form-control" id="new-project-name" name="new-project-name" maxlength="50" required autofocus>
      </div>

      <!-- date due -->
      <div class="form-group">
        <label for="name">Date due *</label>
        <input type="date" class="form-control" id="date-due" name="date-due" required>
      </div>

      <!-- description -->
      <div class="form-group">
        <label for="name">Description</label>
        <textarea class="form-control" rows="1" id="description" name="description"></textarea>
      </div>

      <!-- submit button -->
      <div class="float-right">
        <button type="button" class="btn btn-secondary" onclick="clearInputs()">Reset</button>
        <input type="submit" class="btn btn-primary" id="create-project-btn" value="Create project" disabled>
      </div>
    </form>

  </div>

</div>



</div>
<?php include('footer.php'); ?>

<!-- personal js script -->
<script src="new-project-js.js"></script>


</body>
</html>