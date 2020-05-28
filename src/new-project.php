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

 <div class="container">

  <h1 class="custom-font text-center">New Project</h1> 
  
  <form method="post">
   <!-- project name -->
   <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" class="form-control" id="name" name="new-project-name" maxlength="50" required autofocus>
  </div>

  <!-- description -->
  <div class="form-group">
    <label for="name">Description:</label>
    <textarea class="form-control" rows="1" id="description" name="description"></textarea>
  </div>


  <!-- date due -->
  <div class="form-group">
    <label for="name">Date due:</label>
    <input type="date" class="form-control" id="date-due" name="date-due" required>
  </div>

  <!-- submit button -->
  <div class="float-right">
    <button type="reset" class="btn btn-secondary">Reset</button>
    <input type="submit" class="btn btn-primary" value="Create project">
  </div>

</form>

</div>
<?php include('footer.php'); ?>


<script>     

  $(document).ready(function() {
   enableFlatpickrDates();
   $("#nav-item-new-project").addClass('active');
   autosize($('textarea'));
 });

  function enableFlatpickrDates() {
   flatpickr("#date-due", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    altInput: true,
    altFormat: "F j, Y H:i",
  });
 }

</script>


</body>
</html>