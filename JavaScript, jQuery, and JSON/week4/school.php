<?php
  require_once'pdo.php';
  $term = $_GET['term'].'%';
  $data = array();

  $stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :tm ');
  $stmt ->execute(array(
                        ':tm' => $term
                      ));
  while ($row= $stmt ->fetch(PDO::FETCH_ASSOC)) {
    array_push($data,$row['name']);
  }
  echo(json_encode($data));
 ?>
