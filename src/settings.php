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
    

    <h1 class="custom-font text-center">Settings</h1><br>

    <h5>Update email</h5><br>
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

      <input type="submit" class="btn btn-primary" id="new-email-btn" value="Save email" disabled>
      <input type="reset" class="btn btn-secondary" value="Clear">
    </form>



    <br><br><br>

    <h5>Update password</h5><br>
    
    <form>

      <div class="form-group">
        <label for="old-password">Current password:</label>
        <input type="password" class="form-control update-password-input" name="old-password" id="old-password" required>
      </div>

      <div class="form-group">
        <label for="new-password">New password:</label>
        <input type="password" class="form-control update-password-input" name="new-password" id="new-password" required>
      </div>

      <div class="form-group">
        <label for="confirm-password">Confirm password:
          
        </label>
        <input type="password" name="confirm-password" class="form-control update-password-input" id="confirm-password" required>
        <span class="passwords-must-match-text d-none">&nbsp; Passwords must match</span>
      </div>

      <input type="submit" class="btn btn-primary" id="new-password-btn" value="Save password" disabled>
      <button type="button" class="btn btn-secondary" onclick="clearPasswordInputs()">Clear</button>

    </form>

  </div>


  











  
  <?php include('footer.php'); ?>
  <script src="settings-js.js"></script>





</body>
</html>