<?php
  require_once"pdo.php";
  session_start();
  if (! isset($_SESSION['name'])){
    die("ACCESS DENIED");
  }
  if (isset($_POST['cancel'])) {
    header("Location:index.php");
    return;
  }

  $profid = htmlentities($_GET['profile_id']);
#related to modwel
#validating
#validating
function validateEdu() {
  for ($i=1; $i <=9 ; $i++) {
    if (! isset($_POST['eduyear'.$i])) break;
    if (! isset($_POST['school'.$i])) break;

    $eduyear = $_POST['eduyear'.$i];
    $school = $_POST['school'.$i];

    if ( strlen($eduyear) == 0 || strlen($school) == 0 ) {
      return "All fields are required";
    }
    if ( ! is_numeric($eduyear) ) {
      return "Education year must be numeric";
    }
  }
  return true;
}

function validatePos() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['posyear'.$i]) ) continue;
    if ( ! isset($_POST['posdesc'.$i]) ) continue;

    $posyear = $_POST['posyear'.$i];
    $desc = $_POST['posdesc'.$i];

    if ( strlen($posyear) == 0 || strlen($desc) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($posyear) ) {
      return "Position year must be numeric";
    }
  }
  return true;
}

if (isset($_POST['submit'])) {
  if (strlen($_POST['first_name'])<1 or strlen($_POST['last_name'])<1 or strlen($_POST['email'])<1 or strlen($_POST['headline'])<1 or strlen($_POST['summary'])<1) {
    $_SESSION['error']='All fields are required';
    header("Location:edit.php?profile_id=".$profid);
    return;
  }
  elseif (strpos($_POST['email'],'@') ===false) {
    $_SESSION['error'] = 'Email address must contain @';
    header("Location:edit.php?profile_id=".$profid);
    return;
  }
  elseif (is_string(validateEdu())) {
    $_SESSION['error'] = validateEdu();
    header("Location:edit.php?profile_id=".$profid);
    return;
  }
  elseif (is_string(validatePos())) {
    $_SESSION['error'] = validatePos();
    header("Location:edit.php?profile_id=".$profid);
    return;
  }
  else {
      $stmt = $pdo->prepare("UPDATE Profile SET user_id=:uid, first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su WHERE profile_id=".$profid);
      $stmt ->execute(array(
                      ':uid' => $_SESSION['user_id'],
                      ':fn' => $_POST['first_name'],
                      ':ln' => $_POST['last_name'],
                      ':em' => $_POST['email'],
                      ':he' => $_POST['headline'],
                      ':su' => $_POST['summary']
                    ));
      #$profile_id = $pdo->lastInsertId();
      #for education
      for ($i=1; $i <=$_POST['edurank'] ; $i++) {
        $school = $_POST['school'.$i];
        $eduyear = $_POST['eduyear'.$i];
      #  $school_id =$_POST['school_id'.$i];

        $stmt = $pdo-> prepare('INSERT IGNORE INTO institution (name) VALUES(:sc)');
        $stmt->execute(array(':sc' => $school));
        $stmt =$pdo-> prepare("SELECT institution_id FROM Institution WHERE name=:sc");
        $stmt->execute(array(':sc' => $school));
        $srow = $stmt->fetch(PDO::FETCH_ASSOC);
        $school_id = $srow['institution_id'];

        $stmt = $pdo -> prepare("DELETE FROM Education WHERE profile_id=:pid");
        $stmt ->execute(array(':pid' =>$profid));


        $stmt= $pdo-> prepare('INSERT INTO Education (profile_id, institution_id,rank, year) VALUES (:pid,:iid,:rk,:yr)');
        $stmt ->execute(array(
                              ':pid' => $profid,
                              ':iid' => $school_id,
                              ':rk' => $i,
                              ':yr' => $eduyear
                            ));
      }
      #for position
      for ($i=1; $i <=$_POST['posrank'] ; $i++) {
        $posyear = $_POST['posyear'.$i];
        $description = $_POST['posdesc'.$i];

        $stmt = $pdo-> query("DELETE FROM Position WHERE profile_id=".$profid);

        $stmt = $pdo-> prepare('INSERT INTO Position (profile_id,rank,year,description) VALUES (:pid,:rk,:yr,:dc) ');
        $stmt ->execute(array(
                              ':pid' => $profid,
                              ':rk' => $i,
                              ':yr' => $posyear,
                              ':dc' => $description
                            ));
      }
      $_SESSION['success'] = "Profile updated";
      header('Location:index.php');
      return;
  }
}
#related to view
  $stmt = $pdo ->prepare("SELECT * FROM Profile WHERE profile_id = :id");
  $stmt ->execute(array(':id' =>$_GET['profile_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $firstname = htmlentities($row['first_name']);
  $lastname = htmlentities($row['last_name']);
  $email = htmlentities($row['email']);
  $headline = htmlentities($row['headline']);
  $summary = htmlentities($row['summary']);

  $estmt = $pdo->query("SELECT * FROM Education WHERE profile_id=".$profid." ORDER BY rank");
  $erow_count = $estmt ->rowCount();

  $pstmt = $pdo -> query("SELECT * FROM Position WHERE profile_id=".$profid." ORDER BY rank");
  $prow_count = $pstmt ->rowCount();
 ?>
 <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- jQuery UI library -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <title>Suhailudheen Kadavandi</title>
  </head>
  <body>
    <div class="container">
      <h1>Editing Profile for <?= $_SESSION['name'] ?></h1>
      <?php
        if (isset($_SESSION['error'])){
          echo "<p style='color:red;'>".$_SESSION['error']."</p>";
          unset($_SESSION['error']);
        }
       ?>
      <form class="edit" method="post">
        <p>First Name: <input type="text" name="first_name" size="60" value="<?= $firstname ?>"> </p>
        <p>Last Name: <input type="text" name="last_name" size="60" value="<?= $lastname ?>"> </p>
        <p>Email: <input type="text" name="email" size="60" value="<?= $email ?>"> </p>
        <p>Headline: <br> <input type="text" name="headline" size="60" value="<?= $headline ?>"> </p>
        <p>Summary: <br> <textarea name="summary" rows="8" cols="80">".<?=$summary ?>."</textarea>
        <input type="hidden" name="profile_id" value="<?= $profid ?>"> </p>
        <p>Education: <input type="button" id="edubutton" name="" value="+"> </p><div class="education">
        <?php
          if ($erow_count>0){
            $count =1;
            while ($erow = $estmt->fetch(PDO::FETCH_ASSOC)) {
              $istmt = $pdo->query("SELECT * FROM Institution WHERE institution_id=".$erow['institution_id']);
              $irow = $istmt ->fetch(PDO::FETCH_ASSOC);

              echo ("<div class='edu".$count."'>
                    <p>Year: <input type='text' name='eduyear".$count."' value='".$erow['year']."'>
                    <input type='button' value='-' onclick=eduremover();></p><input type='hidden' name='edurank' value='".$count."'>
                    <p>School: <input type='text' name='school".$count."' value='".$irow['name']."'>
                    <input type='hidden' name='school_id".$count."' value='".$irow['institution_id']."'></p>
                    </div>");
              $count++;
            }
          }
          echo "</div>";
          echo "<p>Position: <input type='button' id='positionbutton' value='+'></p><div class='position'>";
          if ($prow_count>0) {
            $count =1;
            while ($prow = $pstmt->fetch(PDO::FETCH_ASSOC)) {

              echo "<div class='pos".$count."'>
                   <p>Year: <input type='text' name='posyear".$count."' value='".$prow['year']."'>
                   <input type='button' value='-' onclick=posremover();><input type='hidden' name='posrank' value='".$count."'></p>
                   <p><textarea name='desc".$count."' rows='8' cols='80'>".$prow['description']."</textarea><p>
                   </div>";
              $count++;
            }
          }
         ?>
                  </div>
         <p> <input type="submit" name="submit" value="Save"> <input type="submit" name="cancel" value="Cancel"> </p>

      </form>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  -->
    <script>
    //on clicking education +
    educount=0;
    poscount=0;
    if ($('input[name="edurank"]').length !=NaN) {

      educount= $('input[name="edurank"]').length;
      console.log(educount);
    }
    if ($('input[name="posrank"]').length !==NaN)
      poscount =$('input[name="posrank"]').length;

      $(document).ready(function() {
        $("#edubutton").click(function() {
          if (educount >= 9) {
              alert("Maximum of nine education entries exceeded");
              return false;
          }
          educount++;
          var html= '<div class="edu'+educount+'">\
                     <p>Year: <input type="text" name="eduyear'+educount+'" > <input type="button" id="eduremove" value="-" onclick = eduremover(); ></p>\
                     <p>School: <input type="text" class="school" name="school'+educount+'" size="60"></p>\
                     <input type="hidden" name="edurank" value='+educount+'>\
                     </div>';
          $(".education").append(html);

        });
      });
      function eduremover(){
        $(".edu"+educount).remove();
        educount--;
      }
      $(".school").autocomplete({
          source: "school.php",
      });

      //on clicking position +

      $(document).ready(function() {
        $("#positionbutton").click(function() {
          if (poscount>=9) {
            alert("Maximum of nine education entries exceeded");
            return false;
          }
          poscount++;
          var html = '<div class="pos'+poscount+'">\
                     <p>Year: <input type="text" name="posyear'+poscount+'" > <input type="button" id="posremove" value="-" onclick = posremover(); ></p>\
                     <p><textarea name ="posdesc'+poscount+'" rows="8" cols="80"></textarea></p>\
                     <input type="hidden" name="posrank" value='+poscount+'>\
                     </div>';
          $(".position").append(html);
        });
      });
      function posremover() {
        $(".pos"+poscount).remove();
        poscount--;
      }

    </script>
  </body>
</html>
