<?php
include('functions.php'); 
include('session.php'); 
?>

<!DOCTYPE html>
<html>
<head>
  <?php include('header.php'); ?>
  


  <title>Test</title>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="container">

    <h1 class="custom-font">Test Page</h1>

    <textarea id="input" rows="10" class="form-control"></textarea><br>
    
    <button type="button" class="btn btn-primary" onclick="renderMarkdown()">Convert</button>

    




  </div>

  <?php include('footer.php'); ?>

  <script src="test-js.js"></script>

</body>
</html>