<?php 
include('functions.php'); 

// user created a new account
if (isset($_POST['new-email']) && isset($_POST['new-password1']) && isset($_POST['new-password2'])) {
  if ($_POST['new-password1'] == $_POST['new-password2']) {
    insertUser($_POST['new-email'], $_POST['new-password1']);
    session_start();
    $_SESSION['userID'] = getUserID($_POST['new-email']);
    header('Location: index.php');
 }
}

// log in attempt
if (isset($_POST['login-email']) && isset($_POST['login-password'])) {
  if (validateLogin($_POST['login-email'], $_POST['login-password'])) {
    session_start();
    $_SESSION['userID'] = getUserID($_POST['login-email']);
    header('Location: index.php');
    exit;
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

   <h1>Login to MyPlanner</h1>
   <div class="row">
     <div class="col-sm-6">

       <!-- Log In Form -->
       <form method="post">
         <!-- email -->
         <div class="form-group">
          <label for="exampleInputPassword1">Email:</label>
          <input type="email" class="form-control" id="login-email" name="login-email" required>
       </div>

       <!-- password -->
       <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="form-control" id="login-password" name="login-password" required>
       </div>

       <!-- Log In button -->
       <input type="submit" value="Log in" class="btn btn-primary">
    </form>
 </div>

 <div class="col-sm-6">
   <!-- create account form -->
   <form method="post">
     <!-- email -->
     <div class="form-group">
      <label for="exampleInputPassword1">Email:</label>
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
<?php include('footer.php'); ?>
</body>
</html>
