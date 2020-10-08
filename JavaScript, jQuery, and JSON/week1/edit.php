<?php
  require_once'pdo.php';
  session_start();
  if (! $_SESSION['name']){
    die('Not Logged in');
  }

  $id = $_GET['profile_id'];
  $sstmt = $pdo ->prepare('SELECT * FROM Profile WHERE profile_id=:id;');
  $sstmt -> execute(array(':id' => $id));
  $srow = $sstmt ->fetch(PDO::FETCH_ASSOC);

  if (isset($_POST['last_name']) or isset($_POST['first_name']) or isset($_POST['email']) or isset($_POST['headline']) or isset($_POST['summary'])){
    if(strlen($_POST['first_name'])<1 or strlen($_POST['last_name'])<1 or strlen($_POST['email'])<1 or strlen($_POST['headline'])<1 or strlen($_POST['summary'])<1){
      $_SESSION['error']= "All fields are required";
      header("Location:edit.php");
      return;
    }
    elseif (strpos($_POST['email'],'@') === false) {
      $_SESSION['error'] = 'Email address must contain @';

      header('Location:edit.php');
      return;
    }
    else{
      $stmt = $pdo->prepare('UPDATE Profile SET first_name=:fn, last_name=:ln, email=:em, headline=:hl, summary=:su WHERE user_id=:uid;');
      $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':hl' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      $_SESSION['success']= "Profile updated";
      header('Location:index.php');
      return;
    }
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
     <title>Suhailudheen Kadavandi </title>
   </head>
   <body>
     <div class="container">
       <h1>Editing profile for <?= $_SESSION['name'] ?></h1>
       <?php
       if (isset($_SESSION['error'])){
         echo " <p style= 'color:red;'>".$_SESSION['error']."</p> ";
         unset($_SESSION['error']);
       }
       ?>
       <form class="edit" method="post">
         <p>First Name:<br> <input type="text" name="first_name" size="60" value="<?=$srow['first_name']?>"> </p>
         <p>Last Name: <br> <input type="text" name="last_name" size="60" value="<?=$srow['last_name']?>"> </p>
         <p> Email: <br> <input type="text" name="email" size="60" value="<?=$srow['email']?>"> </p>
         <p> Headline: <br> <input type="text" name="headline" size="60" value="<?=$srow['headline']?>"> </p>
         <p> Summary: <br> <textarea name="summary" rows="8" cols="80" ><?=$srow['summary']?></textarea> </p>
         <input type="submit" name="save" value="Save">
         <input type="button" name="cancel" value="Cancel" onclick="window.location= 'index.php'; return false;">


       </form>
     </div>

   </body>
 </html>
