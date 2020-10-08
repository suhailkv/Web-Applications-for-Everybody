<?php
  require_once 'pdo.php';
  session_start();
  if (! $_SESSION['name']){
    die('Not Logged in');
  }

  $first= htmlentities($_POST['first_name']);
  $last= htmlentities($_POST['last_name']);
  $email = htmlentities($_POST['email']);
  $headline = htmlentities($_POST['headline']);
  $summary = htmlentities($_POST['summary']);

  function validatePos() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;

      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];

      if ( strlen($year) == 0 || strlen($desc) == 0 ) {
        return "All fields are required";
      }

      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
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
  elseif ( is_string( validatePos())) {
    $_SESSION['error']=validatePos();
  }
  else{

    $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $first,
      ':ln' => $last,
      ':em' => $email,
      ':he' => $headline,
      ':su' => $summary)
);
$profile_id = $pdo->lastInsertId();
for($i=1; $i<=htmlentities($_POST['rank']); $i++){

  $year = $_POST['year'.$i];
  $desc = $_POST['desc'.$i];

$stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year,description) VALUES (:pid,:rk,:yr,:dc)');
$stmt ->execute(array(
  'pid' => $profile_id,
  ':rk' => $i,
  ':yr' => htmlentities($year),
  ':dc' => htmlentities($desc)
));}
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
       <p>Position: <input type="button" id="addPos" value="+" ></p>
       <div id="position_fields"></div>
       <input type="submit" value="Add">
       <input type="button" name="button" value="Cancel"  onclick="window.location='index.php';return false"></input>
     </form>
   </div>




   <script >
   countPos =0
   function removediv(){
     var x = '#position'.concat(countPos);
     $(x).remove();
     countPos--;
   }
   $(document).ready(function(event){
     $('#addPos').click(function(event){
       countPos++;
       if (countPos<10){
       var large = '<div id="position'+countPos+'">Year:<input type="text" name="year'+countPos+'" value="">\
       <input type="button" value="-" onclick= removediv();><input type="hidden" name="rank" value="'+countPos+'">\
       <br><textarea name="desc'+countPos+'" rows="5" cols="80"></textarea></div>';
       $('#position_fields').append(large);
     }else{alert("Maximum of nine position entries exceeded");}

       });
     });
   </script>

   </body>
 </html>
