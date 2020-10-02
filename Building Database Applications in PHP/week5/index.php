<?php
require_once"pdo.php";
session_start();
?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Welcome to the Automobiles Suhailudheen Kadavandi </title>
   </head>
   <body>
     <h1>Welcome to the Automobiles Database</h1>

     <?php
     if (isset($_SESSION['success'])){
       echo("<p style='color:green';>".$_SESSION['success']."</p>");
       unset($_SESSION['success']);
     }
     if (! isset($_SESSION['name'])){
       echo("<a href='login.php'>Please log in</a>");
       }
       else {
         $stmt = $pdo->query("SELECT * FROM autos");
         $row = $stmt ->fetch(PDO::FETCH_ASSOC);
         if(! $row ==false){
           echo("<table border='1'>"."\n");
           echo "<tr><th>";
           echo "Make";
           echo "</th><th>";
           echo "Model";
           echo "</th><th>";
           echo "Year";
           echo "</th><th>";
           echo "Mileage";
           echo "</th><th>";
           echo "Action";
           echo "</th></tr>";
           while($row = $stmt ->fetch(PDO::FETCH_ASSOC)){
             echo "<tr><td>";
             echo(htmlentities($row['make']));
             echo "</td><td>";
             echo (htmlentities($row['model']));
             echo "</td><td>";
             echo (htmlentities($row['year']));
             echo "</td><td>";
             echo (htmlentities($row['mileage']));
             echo "</td><td>";
             echo ("<a href= 'edit.php?autoid=".$row['autos_id']."'>Edit</a>" );
             echo ("<a href= 'delete.php?autoid=".$row['autos_id']."'>Delete</a>");
             echo "</td></tr>";
           }
         echo "<p><a href='add.php'>Add New Entry</a></p>";
         echo "<p><a href='logout.php'>Logout</a></p>";

         }
         else{
           echo "<p>No rows found</p>";
         }



         }


      ?>


   </body>
 </html>
