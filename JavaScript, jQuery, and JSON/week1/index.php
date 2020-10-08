<?php
require_once"pdo.php";
session_start();
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
     <title>Welcome to Profile Suhailudheen Kadavandi </title>
   </head>
   <body>
     <div class="container">
     <h1>Welcome to the Resume directory</h1>

     <?php
     if (isset($_SESSION['success'])){
       echo("<p style='color:green';>".$_SESSION['success']."</p>");
       unset($_SESSION['success']);
     }
     if (! isset($_SESSION['name'])){
       echo("<a href='login.php'>Please log in</a>");
       $stmt = $pdo->query("SELECT * FROM profile");
       $row = $stmt ->fetch(PDO::FETCH_ASSOC);
       if(! $row ==false){
         echo("<table border='1'>"."\n");
         echo "<tr><th>";
         echo "Name";
         echo "</th><th>";
         echo "Headline";
         echo "</th></tr>";
         while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
           echo "<tr><td>";
           echo("<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a>");
           echo "</td><td>";
           echo (htmlentities($row['headline']));
           echo "</td></tr>";

         }
        }
      }
       else {
         $stmt = $pdo->query("SELECT * FROM profile");
         $row = $stmt ->fetch(PDO::FETCH_ASSOC);
         if(! $row ==false){
           echo("<table border='1'>"."\n");
           echo "<tr><th>";
           echo "Name";
           echo "</th><th>";
           echo "Headline";
           echo "</th><th>";
           echo "Action";
           echo "</th></tr>";
           while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
             echo "<tr><td>";
             echo("<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a>");
             echo "</td><td>";
             echo (htmlentities($row['headline']));
             echo "</td><td>";
             if($row['user_id'] == $_SESSION['user_id']){
               echo ("<a href= 'edit.php?profile_id=".$row['profile_id']."'>Edit</a>" );
               echo ("<a href= 'delete.php?profile_id=".$row['profile_id']."'>Delete</a>");
               echo "</td></tr>";
              }
           }
           echo "</table>";
         }
         else{
           echo "<p>No rows found</p>";
         }
         echo "<p><a href='add.php'>Add New Entry</a></p>";
         echo "<p><a href='logout.php'>Logout</a></p>";
        }


     ?>
   </div>
   </body>
 </html>
