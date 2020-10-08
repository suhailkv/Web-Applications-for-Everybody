<?php
  require_once 'pdo.php';
  session_start();

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
    <h1>Welcome to resume application</h1><br>
    <?php
    if (isset($_SESSION['error'])){
      echo "<p style='color:red'>".$_SESSION['error']."<p>";
      unset($_SESSION['error']);
    }
    elseif (isset($_SESSION['success'])) {
      echo "<p style='color:green'>".$_SESSION['success']."<p>";
      unset($_SESSION['success']);
    }

    $stmt = $pdo->query('SELECT * FROM Profile');
    $row = $stmt ->fetch(PDO::FETCH_ASSOC);

      if (! isset($_SESSION['name'])){
        echo "<a href='login.php'>Please log in</a>";

        if (! $row == false){
          echo("<table border=1><tr><th>Name</th>
                          <th>Headline</th></tr>
                          <tr><td><a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a></td>
                          <td>".htmlentities($row['headline'])."</td></tr>");
          while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
              echo "<tr><td><a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a></td>
              <td>".htmlentities($row['headline'])."</td></tr>";
                    }
                    echo "</table>";
              }
              elseif ($row==false){
                echo "<p>There are Profile</p>";
              }
      }
      elseif (isset($_SESSION['name'])) {
        echo "<a href='logout.php'>Logout</a>";

        if (! $row == false){
          echo("<table border=1><tr><th>Name</th>
                          <th>Headline</th><th>Action</th></tr>
                          <tr><td><a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a></td>
                          <td>".htmlentities($row['headline'])."</td><td>");
          if ($row['user_id'] === $_SESSION['user_id']) {
            echo "<a href='edit.php?profile_id=".$row['profile_id']."'>Edit</a>|<a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a>";
            }
          while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
              echo "</td></tr><tr><td><a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a></td>
                        <td>".htmlentities($row['headline'])."</td><td>";
              if ($row['user_id'] == $_SESSION['user_id']) {
                echo "<a href='edit.php?profile_id=".$row['profile_id']."'>Edit</a>|<a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a>";
                }
                echo "</td></tr>";
                    }
                    echo "</table>";
              }
              elseif ($row==false){
                echo "<p>There are no Profile</p>";
              }
        echo "<p><a href='add.php'>Add New Entry</a><p>";
      }



    ?>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </div>
  </body>
</html>
