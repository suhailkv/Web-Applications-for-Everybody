<?php
  require_once 'pdo.php';
  session_start();
  if (! $_SESSION['name']){
    die('Not Logged in');
  }
if (isset($_POST['last_name']) or isset($_POST['first_name']) or isset($_POST['email']) or isset($_POST['headline']) or isset($_POST['summary'])){
  if(strlen($_POST['first_name'])<1 or strlen($_POST['last_name'])<1 or strlen($_POST['email'])<1 or strlen($_POST['headline'])<1 or strlen($_POST['summary'])<1){
    $_SESSION['error']= "All fields are required";
    header("Location:add.php");
    return;
  }
  elseif (strpos($_POST['email'],'@') === false) {
    $_SESSION['error'] = 'Email address must contain @';

    header('Location:add.php');
    return;
  }
  else{

    $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'])
);
    $_SESSION['success'] = "Profile added";
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
     <title>Suhailudheen Kadavandi</title>
   </head>
   <body>
     <div class="container">
     <h1>Adding Profile for <?= $_SESSION['name'] ?></h1><br>
     <?php
        if (isset($_SESSION['error'])){
          echo "<p style='color:red'>".$_SESSION['error']."</p>";
          #echo $_SESSION['email'];
          unset($_SESSION['error']);
        }
        ?>
     <form class="add" method="post">
       <p> First name:<br><input type="text" name="first_name" size= "60" value=""></p>
       <p>Last name:<br><input type="text" name="last_name" size="60" value=""></p>
       <p>Email:<br><input type="text" name="email" size="60"value=""></p>
       <p>Headline:<br><input type="text" name="headline" size="60" value=""></p>
       <p>summary: <br><textarea name="summary" rows="8" cols="80"></textarea></p><br>
       <input type="submit" value="Add">
       <input type="button" name="button" value="Cancel"  onclick="window.location='index.php';return false"></input>
     </form>
   </div>

   </body>
 </html>
