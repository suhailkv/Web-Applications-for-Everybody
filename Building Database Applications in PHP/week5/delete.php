<?php
  session_start();
  require_once"pdo.php";
  if (!isset($_SESSION['name'])){
    die("Not Logged in");
  }
  $_SESSION['did'] = $_GET['autoid'];
  $sstmt =$pdo ->prepare('SELECT make FROM `autos` WHERE autos_id =:id');
  $sstmt -> execute(array(':id' =>$_SESSION['did']));
  $srow = $sstmt->fetch(PDO::FETCH_ASSOC);

  if (isset($_POST['autoid'])){
    $stmt = $pdo ->prepare('DELETE FROM autos WHERE autos_id=:id');
    $stmt ->execute(array(':id'=>$_POST['autoid']));
    $_SESSION['success']='Record deleted';
    header("Location:index.php");
    return false;
  }

 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Suhailudheen Kadavandi</title>
   </head>
   <body>
     <p>Confirm: Deleting <?= $srow['make']?></p>
     <form class="delete"  method="post"><br>
       <input type="submit" name="delete" value="Delete">
       <input type="hidden" name="autoid" value="<?= $_SESSION['did'] ?>">
       <input type="button" name="cancel" value="Cancel" onclick="window.location='index.php';return false;">

     </form>
   </body>
 </html>
