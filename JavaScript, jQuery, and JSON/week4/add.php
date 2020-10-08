<?php
  require_once'pdo.php';
  session_start();

  if (! isset($_SESSION['name'])){
    die("You are not Logged In");
  }
  #cancel button clicks
  if (isset($_POST['cancel'])){
    header("Location:index.php");
    return;
  }
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
      if ( ! isset($_POST['posyear'.$i]) ) break;
      if ( ! isset($_POST['posdesc'.$i]) ) break;

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

  if (isset($_POST['last_name']) or isset($_POST['first_name']) or isset($_POST['email']) or isset($_POST['headline']) or isset($_POST['summary'])) {
      if (strlen($_POST['first_name'])<1 or strlen($_POST['last_name'])<1 or strlen($_POST['email'])<1 or strlen($_POST['headline'])<1 or strlen($_POST['summary'])<1) {
        $_SESSION['error']='All fields are required';
        header("Location:add.php");
        return;
      }
      elseif (strpos($_POST['email'],'@') ===false) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location:add.php");
        return;
      }
      elseif (is_string(validateEdu())) {
        $_SESSION['error'] = validateEdu();
        header("Location:add.php");
        return;
      }
      elseif (is_string(validatePos())) {
        $_SESSION['error'] = validateEdu();
        header("Location:add.php");
        return;
      }
      else {
          $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');
          $stmt ->execute(array(
                          ':uid' => $_SESSION['user_id'],
                          ':fn' => $_POST['first_name'],
                          ':ln' => $_POST['last_name'],
                          ':em' => $_POST['email'],
                          ':he' => $_POST['headline'],
                          ':su' => $_POST['summary']
                        ));
          $profile_id = $pdo->lastInsertId();
          #for education
          for ($i=1; $i <=$_POST['edurank'] ; $i++) {
            $school = $_POST['school'.$i];
            $eduyear = $_POST['eduyear'.$i];

            $stmt = $pdo-> prepare('INSERT IGNORE INTO institution (name) VALUES(:sc)');
            $stmt->execute(array(':sc' => $school));

            $stmt =$pdo->prepare('SELECT institution_id FROM Institution WHERE Institution.name =:sc ');
            $stmt->execute(array(':sc' =>$school));
            $row = $stmt ->fetch(PDO::FETCH_ASSOC);

            $stmt= $pdo-> prepare('INSERT INTO Education (profile_id,institution_id, rank, year) VALUES (:pid,:iid,:rk,:yr)');
            $stmt ->execute(array(
                                  ':pid' => $profile_id,
                                  ':iid' => $row['institution_id'],
                                  ':rk' => $i,
                                  ':yr' => $eduyear
                                ));
          }
          #for position
          for ($i=1; $i <=$_POST['posrank'] ; $i++) {
            $posyear = $_POST['posyear'.$i];
            $description = $_POST['posdesc'.$i];

            $stmt = $pdo-> prepare(' INSERT INTO Position (profile_id,rank,year,description) VALUES (:pid, :rk,:yr,:dc) ');
            $stmt ->execute(array(
                                  ':pid' => $profile_id,
                                  ':rk' => $i,
                                  ':yr' => $posyear,
                                  ':dc' => $description
                                ));
          }
          $_SESSION['success'] = "Profile added";
          header('Location:index.php');
          return;
      }
  }
 ?>
 <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css"><link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css">
 <!--link rel="stylesheet" href="/resources/demos/style.css" -->
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- jQuery library
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- jQuery UI library -
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS ->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <!-- Optional JavaScript -->
    <!-- jQuery UI library
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> ->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  -->

    <title>Suhailudheen Kadavandi</title>
  </head>
  <body>
    <div class="container">
      <h1>Adding Profile for <?= $_SESSION['name'] ?></h1>
      <?php
        if (isset($_SESSION['error'])){
          echo "<p style='color:red'>".$_SESSION['error']."</p>";
          unset($_SESSION['error']);
        }
       ?>
      <form class="add"  method="post">
        <p>First Name: <input type="text" name="first_name" value="" size="60"> </p>
        <p>Last Name: <input type="text" name="last_name" value="" size="60"> </p>
        <p>Email: <input type="text" name="email" value="" size="60"> </p>
        <p>Headline: <br> <input type="text" name="headline" value="" size="60"> </p>
        <p> Summary: <br> <textarea name="summary" rows="8" cols="80"></textarea> </p>
        <p>Education: <input id="edubutton" type="button" value="+"> </p>
        <div class="education">

        </div>
        <p>Position: <input id="positionbutton" type="button" value="+"> </p>
        <div class="position">

        </div>
        <p> <input type="submit"  value="Add"> <input type="submit" name="cancel" value="Cancel"> </p>
      </form>

    </div>


    <script>
    //on clicking education +
      educount=0;
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
       source: 'school.php'
   });


      //on clicking position +
      poscount=0;
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
