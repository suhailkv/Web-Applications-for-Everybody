<?php
session_start();
require_once "pdo.php";
$salt = 'XyZzy12*_';
#$md5 = hash('md5', 'php123')
$stored_hash =hash('md5', 'XyZzy12*_php123');;  // Pw is php123

if (isset($_POST['email']) and isset($_POST['pass'])){
  $stmt = $pdo ->prepare('SELECT user_id, name, email,password FROM users WHERE email =:em');
  $stmt ->execute(array(':em' => $_POST['email']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $md5u = hash('md5', $salt.$_POST['pass']);
  if (! $_POST['email'] ==$row['email'] or $md5u ==$row['pass']){
    $_SESSION['error'] = "Incorrect password";
    header("Location:login.php");
    return;
  }
  else {
    $_SESSION['name'] = $row['name'];
    $_SESSION['user_id'] = $row['user_id'];
    header("Location:index.php");
    return;
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

      if (isset($_SESSION["error"])){
        echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
      }
      ?>
     <form method="post">
       User Name <input type="text" name="email" id="email"><br>
       Password <input type="password" name="pass" id="pass"><br>
       <input type="submit" name="submit" value="Log In" onclick="return doValidate();">
       <input type="button" onclick="location.href='index.php/'; return false"; value="Cancel">

     </form>
     <script type="text/javascript">
      function doValidate() {
        var email = document.getElementById('email').value;
        var pass = document.getElementById('pass').value;
        if (email== "" || pass == ""){
          alert("Both fields must be filled out");
          return false;
        }
        else if (email.indexOf('@') == -1) {
          alert("Invalid email address");
          return false;
        }
        return true;
      }

     </script>
   </body>
 </html>
