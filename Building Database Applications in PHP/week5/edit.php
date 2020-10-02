<?php
session_start();
require_once("pdo.php");
  if (! isset($_SESSION['name'])){
    die("Not Logged in");
  }
  $stmt = $pdo->prepare('SELECT make,model,year,mileage FROM autos WHERE autos_id =:id');
  $stmt -> execute(array(':id'=> $_GET['autoid']));

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $make = htmlentities($row['make']);
  $model = htmlentities($row['model']);
  $year = htmlentities($row['year']);
  $mileage= htmlentities($row['mileage']);

  $_SESSION['id'] =$_GET['autoid'];

  if ( isset($_POST['make']) and isset($_POST['model']) and isset($_POST['year']) and isset($_POST['mileage'])){

    if (strlen($_POST['make']) < 1 or strlen($_POST['model'])<1 or strlen($_POST['year'])<1 or strlen($_POST['mileage'])<1 ){
      $_SESSION['error']= "All fields are required";
      header("Location:edit.php?autoid=".$_SESSION['id']);
      return;
    }
    elseif (is_numeric($_POST['year'])==false or is_numeric($_POST['mileage'])==false) {
      $_SESSION['error']= "Year must be an integer";
      header("Location:edit.php?autoid=".$_SESSION['id']);
      return;
    }
    else {
      $stmt = $pdo->prepare('UPDATE autos SET make=:mk,model= :md,year= :yr,mileage= :ml WHERE autos_id=:id');
      $stmt -> execute(array(
          ':mk' => $_POST['make'],
          ':md' => $_POST['model'],
          ':yr' => $_POST['year'],
          ':ml' => $_POST['mileage'],
          ':id' => $_SESSION['id'])
        );
        $_SESSION['success'] = 'Record edited';
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
     <h1>Editing Automobile</h1>
     <form class="edit" method="post">
       Make <input type="text" name="make" value="<?= $make ?>"><br>
       Model <input type="text" name="model" value="<?= $model ?>"><br>
       Year <input type="text" name="year" value="<?= $year ?>"><br>
       Mileage <input type="text" name="mileage" value="<?= $mileage ?>"><br>
       <input type="submit" name="submit" value="Save">
       <input type="button" name="cancel" value="Cancel" onclick="window.location='index.php'; return false;">


     </form>

   </body>
 </html>
