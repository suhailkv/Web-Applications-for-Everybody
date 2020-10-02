<?php
session_start();
require_once'pdo.php';
if (! isset($_SESSION['name'])){
  die('Not logged in');
}
 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Suhail Kadavandi</title>
   </head>
   <body>
     <?php
     echo("<h1>Tracking Autos for".$_SESSION['name']."</h1>" );
     if(isset($_SESSION['success'])){
       echo('<p style="color: green;">'.$_SESSION["success"]."</p>\n");
       unset($_SESSION['success']);
     }
     ?>
     <h2>Automobiles</h2>
     <?php
     if (isset($_SESSION['make'])&& isset($_SESSION['year'])&& isset($_SESSION['mileage'])){
      echo("<ul><li>".htmlentities($_SESSION['year'])."|".htmlentities($_SESSION['make'])."/".htmlentities($_SESSION['mileage'])."</li></ul>");
      unset($_SESSION['make']);
      unset($_SESSION['year']);
      unset($_SESSION['mileage']);
    }
      ?>


     <a href="add.php">Add New | </a><a href="logout.php">Logout</a>

   </body>
 </html>
