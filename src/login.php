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
                    <input type="email" class="form-control" id="login-email" required>
                  </div>

                  <!-- password -->
                  <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="login-password" required>
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
                    <input type="email" class="form-control" id="new-email" required>
                  </div>

                  <!-- password -->
                  <div class="form-group">
                    <label for="new-password1">Password:</label>
                    <input type="password" class="form-control" id="new-password1" required>
                  </div>

                  <!-- password -->
                  <div class="form-group">
                    <label for="new-password2">Password again:</label>
                    <input type="password" class="form-control" id="new-password2" required>
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
