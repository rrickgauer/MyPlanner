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
    <h1 class="custom-font mb-5">Test Page</h1>

  
    <table class="my-table " data-toggle="table" data-show-columns="true" data-show-columns-toggle-all="true">
      <thead>
        <tr>
          <th>Item_ID</th>
          <th>Name</th>
          <th>Checklists count</th>
          <th>Notes count</th>
          <th>Date created</th>
          <th>Date due</th>
          <th>Completed</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $count = 0;
          while ($count < 50) {
            echo '<tr><td>Data</td><td>Data</td><td>Data</td><td>Data</td><td>Data</td><td>Data</td><td>Data</td><td>Data</td></tr>';
            $count++;
          }
        ?>

      </tbody>
    </table>



  </div>

  <?php include('footer.php'); ?>

  <script src="test-js.js"></script>

</body>
</html>