<?php
session_start();
include('functions.php');

// user attempted to update email
if (isset($_POST['new-email-input'])) {
  $result = updateUserEmail($_SESSION['userID'], $_POST['new-email-input'])->rowCount();
  $emailUpdated = false;
  if ($result == 1) {
    $emailUpdated = true;
  }
}

// user attempted to update password
else if (isset($_POST['old-password'], $_POST['new-password'], $_POST['confirm-password'])) {
  $passwordUpdated = true;
  $oldPassword    = $_POST['old-password'];
  $newPassword     = $_POST['new-password'];
  $confirmPassword = $_POST['confirm-password'];

  // new password and confirm password do not match
  if ($newPassword != $confirmPassword) {
    $passwordUpdated = false;
  }

  else {
    $userInfo = getUserInfo($_SESSION['userID'])->fetch(PDO::FETCH_ASSOC);
    $currentUserPassword = $userInfo['password'];

    // the submitted old password does not match with the one in the database
    if (!password_verify($oldPassword, $currentUserPassword)) {
      $passwordUpdated = false;
    } 

    // update the password
    else {
      updateUserPassword($_SESSION['userID'], $newPassword);
      $passwordUpdated = true;
    }
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

      // update email attempt
    if (isset($_POST['new-email-input'], $emailUpdated)) {
      if ($emailUpdated) {
        echo getAlert('Email updated successfully');
      } else {
        echo getAlert('Email not updated', 'danger');
      }
    }

      // update password attempt
    else if (isset($_POST['old-password'], $_POST['new-password'], $_POST['confirm-password'])) {
      if ($passwordUpdated) {
        echo getAlert('Password updated successfully');
      }

      else {
        echo getAlert('<b>Error!</b> Password not updated', 'danger');
      }
    }
    ?>
    

    <h1 class="custom-font text-center mt-5">Settings</h1>

    <div class="card-deck mt-5">

      <div class="card">
        <div class="card-body">
          <h3 class="mb-4 text-center">Update email</h3>
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

            <div class="float-right">
              <input type="reset" class="btn btn-secondary" value="Clear">
              <input type="submit" class="btn btn-primary" id="new-email-btn" value="Save email" disabled>
            </div>

          </form>
        </div>
      </div>
      
      <div class="card">
        <div class="card-body">
          <h3 class="mb-4 text-center">Update password</h3>

          <form method="post">
            <div class="form-group">
              <label for="old-password">Current password:</label>
              <input type="password" class="form-control update-password-input" name="old-password" id="old-password" required>
            </div>

            <div class="form-group">
              <label for="new-password">New password:</label>
              <input type="password" class="form-control update-password-input" name="new-password" id="new-password" required>
            </div>

            <div class="form-group">
              <label for="confirm-password">Confirm password:</label>
              <input type="password" name="confirm-password" class="form-control update-password-input" id="confirm-password" required>
              <div class="passwords-must-match-text d-none">&nbsp; Passwords must match</div>
            </div>
            
            <div class="float-right">
              <button type="button" class="btn btn-secondary" onclick="clearPasswordInputs()">Clear</button>
              <input type="submit" class="btn btn-primary" id="new-password-btn" value="Save password" disabled>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>
  
  <?php include('footer.php'); ?>
  <script src="settings-js.js"></script>

</body>
</html>