<?php
require_once 'pdo.php';
$success = false;
$failure = false;
$new_entry = false;

if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
if ( isset($_POST['logout']) ){
    header('Location: index.php');
    return;
}
if (isset($_POST['add'])){
  $new_entry = "<h1>Automobiles</h1><br><ui><li> ".htmlentities($_POST['year']).htmlentities($_POST['make']). htmlentities($_POST['mileage'])." </li></ui>";
}
if (isset($_POST['make']) && isset($_POST['year'])&& isset($_POST['mileage'])){
  if ( isset($_POST['make']) && strlen($_POST['make'])<1){
  $failure = "Make is required";
}
elseif ((is_numeric($_POST['year']) == false) || (is_numeric($_POST['mileage']) == false)){
  $failure = "Mileage and year must be numeric";
}

else{
  $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'])
);
    $success = "Record inserted";
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
      echo("<h1> Tracking Autos for ".$_GET['name']."</h1>");
      if ($failure !== false){
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
      }
      if ($success !== false){
        echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
      }
      ?>

      <form class="make"  method="post">
        Make: <input type="text" name="make" ><br>
        Year: <input type="text" name="year"><br>
        Mileage: <input type="text" name="mileage" ><br>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="logout" value="Logout">
      </form>
<?php
if ($new_entry !== false){
  echo $new_entry;
} ?>
   </body>
 </html>
