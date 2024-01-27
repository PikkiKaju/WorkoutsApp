<?php

require_once "../source/connect.php";

try {
  if (isset($_POST['exercise_name'])) {
    if ($connection = new mysqli($db_host, $db_user, $db_password, $db_name)) {
      $exercise_name = $_POST['exercise_name'];
      $MySQL_query_remove_exercise = $connection->prepare("DELETE FROM  `$db_exercises_list_name` WHERE ExerciseName = (?)");
      $MySQL_query_remove_exercise->bind_param('s', $exercise_name);
      
      if ($MySQL_query_remove_exercise->execute()) {
        echo "Exercise removed from list";
      } else {
        throw new Exception("Could not remove exercise from list", 1);
      }
    } else {
      throw new Exception("Unable to connect", 1);
      if ($connection->connect_errno) {
        throw "Error: ".$connection->connect_errno." - ".$connection->connect_error;
      }
    }
    $connection->close();
  } else {
    throw new Exception("Exercise name not received", 1);
  }
} catch (Exception $exception) {
  throw $exception;
}

?>