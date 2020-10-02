<?php
session_start();
require_once"pdo.php";

if (!isset($_SESSION['name'])){
  die("Not Logged in");

}
if ( isset($_POST['make']) and isset($_POST['model']) and isset($_POST['year']) and isset($_POST['mileage'])){

  if (strlen($_POST['make']) < 1 or strlen($_POST['model'])<1 or strlen($_POST['year'])<1 or strlen($_POST['mileage'])<1 ){
    $_SESSION['error']= "All fields are required";
    header("Location:add.php");
    return;
  }
  elseif (is_numeric($_POST['year'])==false or is_numeric($_POST['mileage'])==false) {
    $_SESSION['error']= "Year must be an integer";
    header("Location:add.php");
    return;
  }
  else {
    $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :ml)');
    $stmt -> execute(array(
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':ml' => $_POST['mileage'])
      );
      $_SESSION['success'] = 'Record added';
      header("Location:index.php");
      return;
  }
}
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Suhailudheen Kadavandi</title>
   </head>
   <body>
     <h1>Tracking Autos for <?= $_SESSION['name'] ?></h1>
     <?php
        if(isset($_SESSION['error'])){
          echo("<p style='color:red';>".$_SESSION['error']."</p>");
          unset($_SESSION['error']);
        }
        ?>
     <form class="add"  method="post">
       Make <input type="text" name="make" value=""><br>
       Model <input type="text" name="model" value=""><br>
       Year <input type="text" name="year" value=""><br>
       Mileage <input type="text" name="mileage" value=""><br>
       <input type="submit" name="submit" value="Add">
       <input type="button" name="cancel" value="Cancel" onclick="window.location='index.php';return false;">
     </form>

   </body>
 </html>
