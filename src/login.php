<?php 
include('functions.php'); 

// user created a new account
if (isset($_POST['new-email']) && isset($_POST['new-password1']) && isset($_POST['new-password2'])) {
  if ($_POST['new-password1'] == $_POST['new-password2']) {
    insertUser($_POST['new-email'], $_POST['new-password1']);
    session_start();
    $_SESSION['userID'] = getUserID($_POST['new-email']);
    header('Location: index.php');
    exit;
  } else {
    $accountCreationAttempt = false;  // used to set an alert on the page
  }
}

// log in attempt
if (isset($_POST['login-email']) && isset($_POST['login-password'])) {
  if (validateLogin($_POST['login-email'], $_POST['login-password'])) {
    session_start();
    $_SESSION['userID'] = getUserID($_POST['login-email']);
    header('Location: index.php');
    exit;
  } else {
    $loginAttempt = false;  // used to set an alert on the page
  }
}

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <?php include('header.php'); ?>
  <title>Login to MyPlanner</title>
</head>
<body>
  <div class="container">
    <h1 class="custom-font text-center m-5">MyPlanner</h1>

    <div class="card-deck">

        <div class="card">
          <div class="card-body">            
            <h2 class="custom-font text-center mb-4">Login</h2>

            <?php 
              // user attempted an invalid login
              if (isset($loginAttempt) && !$loginAttempt) {
                echo getAlert('Login failed.', 'danger');
              }
            ?>

            <!-- Log In Form -->
            <form method="post">
              <!-- email -->
              <div class="form-group">
                <label for="login-email">Email:</label>
                <input type="email" class="form-control" id="login-email" name="login-email" required autofocus>
              </div>

              <!-- password -->
              <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" class="form-control" id="login-password" name="login-password" required>
              </div>

              <!-- Log In button -->
              <input type="submit" value="Log in" class="btn btn-primary">
            </form>
          </div>
        </div>
     
        <div class="card">
          <div class="card-body">
            <h2 class="custom-font text-center mb-4">Create account</h2>

            <?php 
              // user attempted an invalid account creation
              if (isset($accountCreationAttempt) && !$accountCreationAttempt) {
                echo getAlert('Account was not created. Please try again.', 'danger');
              }
            ?>

            <!-- create account form -->
            <form method="post">
              <!-- email -->
              <div class="form-group">
                <label for="new-email">Email:</label>
                <input type="email" class="form-control" id="new-email" name="new-email" required>
              </div>

              <!-- password -->
              <div class="form-group">
                <label for="new-password1">Password:</label>
                <input type="password" class="form-control" id="new-password1" name="new-password1" required>
              </div>

              <!-- password -->
              <div class="form-group">
                <label for="new-password2">Password again:</label>
                <input type="password" class="form-control" id="new-password2" name="new-password2" required>
              </div>

              <!-- Create account button -->
              <input type="submit" value="Create account" class="btn btn-primary">
            </form>
          </div>
        </div>

    </div>

  </div>
  <?php include('footer.php'); ?>
</body>
</html>
