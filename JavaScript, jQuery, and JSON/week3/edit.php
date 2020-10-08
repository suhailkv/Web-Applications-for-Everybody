<?php
  require_once'pdo.php';
  session_start();
  if (! $_SESSION['name']){
    die('Not Logged in');
  }

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

  $id = $_GET['profile_id'];
  $sstmt = $pdo ->prepare('SELECT * FROM Profile WHERE profile_id=:id;');
  $sstmt -> execute(array(':id' => $id));
  $srow = $sstmt ->fetch(PDO::FETCH_ASSOC);
  $pstmt = $pdo -> prepare('SELECT * FROM Position WHERE profile_id=:id;');
  $pstmt -> execute(array(':id' => $id));
  $prow = $pstmt ->fetch(PDO::FETCH_ASSOC);
  #print_r($prow);

  if (isset($_POST['last_name']) or isset($_POST['first_name']) or isset($_POST['email']) or isset($_POST['headline']) or isset($_POST['summary'])){
    if(strlen($_POST['first_name'])<1 or strlen($_POST['last_name'])<1 or strlen($_POST['email'])<1 or strlen($_POST['headline'])<1 or strlen($_POST['summary'])<1){
      $_SESSION['error']= "All fields are required";
      header("Location:edit.php?profile_id=".$id);
      return;
    }
    elseif (strpos($_POST['email'],'@') === false) {
      $_SESSION['error'] = 'Email address must contain @';

      header("Location:edit.php?profile_id=".$id);
      return;
    }
    elseif ( is_string( validatePos())) {
      $_SESSION['error']=validatePos();
      header("Location:edit.php?profile_id=".$id);
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
         <p>Position: <input type="button" id="addPos" value="+" ></p>
         <div id="position_fields">
         <?php
            if ($prow){
              $count =2;
              echo "<div id='position1'><p>Year:<input type='text' name ='year1' value='".$prow['year']."'>";
              echo " <input type='button' value='-' onclick= removediv();><input type='hidden' name='rank' value='1'></p>";
              echo "<textarea name='desc1' rows='5' cols='80'>".$prow['description']."</textarea></div>";
              while ($prow = $pstmt ->fetch(PDO::FETCH_ASSOC)) {
                echo "<div id ='position".$count."'><p>Position:<input type='button' id='addPos' value='+' ><br>Year:<input type='text' name='year".$count."' value='".$prow['year']."'>";
                echo " <input type='button' value='-' onclick= removediv();><input type='hidden' name='rank' value='".$count."'></p>";
                echo "<textarea name='desc".$count."' rows='5' cols='80'>".$prow['description']."</textarea></div>";
                $count++;
              }
              echo "</div>";
            }
          ?>
         <input type="submit" name="save" value="Save">
         <input type="button" name="cancel" value="Cancel" onclick="window.location= 'index.php'; return false;">
       </form>
     </div>
     <script >
     //https://stackoverflow.com/questions/2109472/how-to-get-a-value-of-an-element-by-name-instead-of-id/2109478
     if($('input[type=hidden]').length>0){
       countPos= $('input[type=hidden]').length;
     }
     else {
       countPos =0;
     }

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
