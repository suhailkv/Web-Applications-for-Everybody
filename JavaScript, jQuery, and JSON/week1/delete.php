<?php
  session_start();
  require_once'pdo.php';

  if (! isset($_SESSION['name'])){
    die("Not Logged in");
  }

  $id = $_GET['profile_id'];

  $sstmt = $pdo -> prepare('SELECT first_name,last_name FROM Profile WHERE profile_id=:id');
  $sstmt-> execute(array(':id' => $id));
  $srow = $sstmt ->fetch(PDO::FETCH_ASSOC);

  if(isset($_POST['last_name'])){
    $stmt = $pdo -> prepare('DELETE FROM Profile WHERE profile_id=:ln');
    $stmt ->execute(array(':ln' => $id));
    $_SESSION['success']= "Profile deleted";
    header('Location:index.php');
    return;
  }


 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Suhailudheen Kadavandi</title>
  </head>
  <body>
    <div class="container">
      <h1>Deleting Profile</h1>
      <form class="delete" method="post">
      <p>First Name:<?= $srow['first_name'] ?></p>
      <p>Last Name:<?= $srow['last_name'] ?></p>
      <input type="hidden" name="last_name" value=".<?= $srow['last_name']?>">
      <input type="submit" name="submit" value="Delete">
      <input type="button" name="cancel" value="Cancel" onclick="window.location= 'index.php';return false;">
      </form>

    </div>

  </body>
</html>
