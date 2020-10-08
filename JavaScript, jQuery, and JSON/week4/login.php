<?php
  require_once'pdo.php';
  session_start();
  #if clicked on cancel
  if (isset($_POST['cancel'])){
    header("Location:index.php");
    return;
  }
  #validating
  $salt = 'XyZzy12*_';
  if (isset($_POST['email']) and isset($_POST['pass'])){
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE email =:em');
    $stmt ->execute(array(':em' =>$_POST['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (hash('md5', $salt.htmlentities($_POST['pass'])) == $row['password']) {
      $_SESSION['name'] =$row['name'];
      $_SESSION['user_id']= $row['user_id'];
      header("Location:index.php");
      return;
    }
    else {
      $_SESSION['error'] ="Incorrect password";
      header("Location:login.php");
      return;
    }
  }
 ?>
 <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Suhailudheen Kadavandi</title>
  </head>
  <body>
    <div class="container">
      <?php
        if (! isset($_SESSION['name'])) {
          echo "<h1>Please Log In</h1>";
          if (isset($_SESSION['error'])){
            echo "<p style='color:red;'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
          }
          echo "<form method='post'>
                <p>Email<input type='text' name='email'></p>
                <p>Password<input type='password' name='pass'></p>
                <input type='submit' value='Log In'><input type='submit' name='cancel' value='Cancel'>
                </form>";
        }
        elseif (isset($_SESSION['name'])) {
          echo "<h1>You are already Logged in</h1>";
          echo "<p><a href='index.php'>Main page</a></p>";
          echo "<p><a href='logout.php'>Click here to Logout</a></p>";
        }
       ?>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script>
    //alert("heyyay");
    $(document).ready(function(){
      $("input[value='Log In']").click(function(){
        var email = $("input[name='email']").val();
        var pass = $("input[name='pass']").val();
        if (email =="" || pass =="") {
          alert("Both fields must be filled out");
          return false;
        }
        else if (email.indexOf('@') == -1) {
          alert("Invalid email address");
          return false;
        }
        return true;
      });
    });

    </script>
  </body>
</html>
