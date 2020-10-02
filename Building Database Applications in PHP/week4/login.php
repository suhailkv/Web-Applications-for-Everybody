<?php
session_start();
require_once "pdo.php";
$salt = 'XyZzy12*_';
#$md5 = hash('md5', 'php123')
$stored_hash =hash('md5', 'XyZzy12*_php123');;  // Pw is php123

if (isset($_POST['email']) && isset($_POST['pass'])){
  if (strlen($_POST['email'])<1 || strlen($_POST['pass'])<1){
    $failure = "Email and password are required";
  }
  elseif (strpos($_POST['email'],'@') == false) {
    $failure = "Email must have an at-sign (@)";
  }
  else {
      $check = hash('md5', $salt.$_POST['pass']);
      if ($check == $stored_hash) {
        $_SESSION['name']=$_POST['email'];
        header("Location: view.php");
        return;
        error_log("Login success ".$_POST['email']);

      }
      else {
        $_SESSION["error"] = "Incorrect password";
        header('Location: login.php');
        return;
        error_log("Login fail ".$_POST['email']." $check");
      }
  }

}

 ?>


 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Suhailudheen Kadavandi 8defc2ba</title>

     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

   </head>
   <body>
     <h1>Please Login</h1>
     <?php
      if ($failure !== false){
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
      }
      if (isset($_SESSION["error"])){
        echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
      }
      ?>
     <form method="post">
       User Name: <input type="text" name='email'><br>
       Password: <input type="password" name="pass"><br>
       <input type="submit" name="submit" value="Log In">
       <input type="button" onclick="location.href='index.php/'; return false"; value="Cancel">

     </form>
   </body>
 </html>
