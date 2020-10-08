<?php
  require_once"pdo.php";
  session_start();
  if (! isset($_SESSION['name'])){
    die('ACCESS DENIED');
}
    $proid= $_GET['profile_id'];
    $stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id=:id');
    $stmt->execute(array(':id'=>$proid));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <h1>Profile information</h1>
    <p>First Name:<?= $row['first_name'] ?></p>
    <p>Last Name:<?= $row['last_name'] ?></p>
    <p>Email:<?= $row['email'] ?></p>
    <p>Headline:<?= $row['headline'] ?></p>
    <p>Summary:<?= $row['summary'] ?></p>
    <a href="index.php">Done</a>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</div>
  </body>
</html>
