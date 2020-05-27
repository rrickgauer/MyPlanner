<?php
session_start();
include('functions.php');


if (isset($_POST['new-email-input'])) {
  $result = updateUserEmail($_SESSION['userID'], $_POST['new-email-input'])->rowCount();
  $emailUpdated = false;
  if ($result == 1) {
    $emailUpdated = true;
  }
}

$userInfo = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
  <?php include('header.php'); ?>
  <title>Settings</title>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="container">
    
    <?php
      if (isset($_POST['new-email-input'], $emailUpdated)) {
        if ($emailUpdated) {
          echo getAlert('Email updated successfully');
        } else {
          echo getAlert('Email not updated', 'danger');
        }
      }
    ?>
    

    <h1 class="custom-font text-center">Settings</h1>

    <h5>Update email</h5>
    <form method="post">

      <!-- old email -->
      <div class="form-group">
        <label for="old-email-input">Old email:</label>
        <input type="email" class="form-control" name="new-email-old" id="old-email-input" value="<?php echo $userInfo['email']; ?>" readonly>
      </div>
  
      <!-- new email input -->
      <div class="form-group">
        <label for="new-email-input">New email:</label>
        <input type="email" class="form-control update-button" name="new-email-input" id="new-email-input" data-button-id="#new-email-btn" required>
      </div>

      <input type="submit" class="btn btn-primary" id="new-email-btn" value="Update" disabled>
      <input type="reset" class="btn btn-secondary" value="Clear">
    </form>
 
  </div>


  
  <?php include('footer.php'); ?>


  <script>
    
    $(document).ready(function() {
      $("#nav-item-settings").addClass('active');

      addEventListeners();
    });

    function addEventListeners() {
      $(".update-button").on("keyup", function () {
        var buttonID = $(this).attr("data-button-id");
        enableButtonFromInput($(buttonID), this);
      });
    }

    // enables-disables a button based on if the specified input is empty
    function enableButtonFromInput(button, input) {
      var inputLength = $(input).val().length;

      if (inputLength > 0) {
        $(button).prop('disabled', false); // set to enabled
      }
      else {
        $(button).prop('disabled', true); // set to disabled
      }
    }


  </script>





</body>
</html>