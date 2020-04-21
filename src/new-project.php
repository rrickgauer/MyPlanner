<?php include('functions.php'); ?>
<?php include('session.php'); ?>

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
		<h1>New Project</h1>	
		<form>
         <!-- project name -->
         <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" class="form-control" id="name" name="name" required autofocus>
       </div>

       <!-- description -->
       <div class="form-group">
         <label for="name">Description:</label>
         <textarea class="form-control" rows="5" id="description" name="description"></textarea>
      </div>


      <!-- date due -->
      <div class="form-group">
         <label for="name">Date due:</label>
         <input type="date" class="form-control" id="date-due" name="date-due" required>
      </div>

      <!-- submit button -->
      <input type="submit" class="btn btn-primary float-right" value="Create project">
   </form>

</div>
<?php include('footer.php'); ?>


<script>     

   $(document).ready(function() {
      enableFlatpickrDates();
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