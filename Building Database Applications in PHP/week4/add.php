<?php
require_once 'pdo.php';
session_start();
if (! isset($_SESSION['name'])){
  die('Not logged in');
}

if (isset($_POST['make']) && isset($_POST['year'])&& isset($_POST['mileage'])){
  if ( isset($_POST['make']) && strlen($_POST['make'])<1){
  $_SESSION['error'] = "Make is required";
  header('Location:add.php');
  return;
}
elseif ((is_numeric($_POST['year']) == false) || (is_numeric($_POST['mileage']) == false)){
  $_SESSION['error'] = "Mileage and year must be numeric";
  header('Location:add.php');
  return;
}

else{
  $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'])
);
    $_SESSION['make']=$_POST['make'];
    $_SESSION['year']=$_POST['year'];
    $_SESSION['mileage']= $_POST['mileage'];
    $_SESSION['success']= "Record inserted";
    header("Location:view.php");
    return;
}
}
 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Suhailudheen Kadavandi 8defc2ba</title>
   </head>
   <body>
     <?php
      echo("<h1> Tracking Autos for ".$_SESSION['name']."</h1>");
      if (isset($_SESSION['error'])){
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
      }

      ?>

      <form class="make"  method="post">
        Make: <input type="text" name="make" ><br>
        Year: <input type="text" name="year"><br>
        Mileage: <input type="text" name="mileage" ><br>
        <input type="submit" name="add" value="Add">
        <input type="submit" onclick="window.location='logout.php';return false; "name="logout" value="Logout">
      </form>

   </body>
 </html>
