<?php
  require_once'pdo.php';
  session_start();

  $profid =htmlentities($_GET['profile_id']);
  $stmt = $pdo->query("SELECT * FROM Profile WHERE profile_id =".$profid);
  $row = $stmt ->fetch(PDO::FETCH_ASSOC);
  #if row is empty
  if ($row ==false){
    $_SESSION['error']="Could not load profile";
    header("Location:index.php");
    return;
  }

  $firstname = htmlentities($row['first_name']);
  $lastname = htmlentities($row['last_name']);
  $email = htmlentities($row['email']);
  $headline = htmlentities($row['headline']);
  $summary = htmlentities($row['summary']);

  $stmt = $pdo ->query("SELECT * FROM Education WHERE profile_id=".$profid." ORDER BY rank");
  $row_count = $stmt->rowCount();

  $pstmt = $pdo->query("SELECT * FROM Position WHERE profile_id=".$profid." ORDER BY rank");
  $prow_count = $pstmt->rowCount();
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
      <h1>Profile information</h1><br>
        <p>First Name:<?= $firstname ?></p>
        <p>Last Name: <?= $lastname ?></p>
        <p>Email: <?= $email ?></p>
        <p>headline: <?= $headline ?></p>
        <p>Summary: <?= $summary ?></p>
        <?php
        if ($row_count >0) {
          echo "<p>Education</p>
                <div class='education'><p><ul>";
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $istmt = $pdo->query('SELECT name FROM Institution WHERE institution_id ='.$row['institution_id']);
            $irow = $istmt->fetch(PDO::FETCH_ASSOC);

            echo" <li>".$row['year'].":".$irow['name']."</li>";
          }
          echo "</ul></p></div>";
        }
        if ($prow_count>0) {
          echo "<p>Position</p>
                <div class='position'><p><ul>";
          while ($prow=$pstmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>".$prow['year'].":".$prow['description']."</li>";
          }
          echo "</ul></p></div>";
        }
        ?>
        <p> <a href="index.php">Done</a> </p>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>
