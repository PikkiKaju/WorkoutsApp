<?php

session_start();
require_once "../source/connect.php";

try {
  if (isset($_SESSION["workout_displayed"])) { 
    if ($connection = new mysqli($db_host, $db_user, $db_password, $db_name)) {
      $workout_table_name = $_SESSION["workout_displayed"];
      $MySQL_query_delete_table = "DROP TABLE `$workout_table_name`";
      $MySQL_query_remove_from_list = "DELETE FROM $db_workout_list_name WHERE WorkoutTableName = '$workout_table_name'";

      if ($connection->query($MySQL_query_delete_table)) {
        if ($connection->query($MySQL_query_remove_from_list)) {
          echo "Workout has been deleted.";
          unset($_SESSION["workout_displayed"]);
        } else {
          throw new Exception("Could not delete workout from list", 1);
        }
      } else {
        throw new Exception("Could not delete table", 1);
      }
    } else {
      throw new Exception("Unable to connect", 1);
      if ($connection->connect_errno) {
        throw "Error: ".$connection->connect_errno." - ".$connection->connect_error;
      }
    }
  } else {
    throw new Exception("Workout name not received", 1);
  }
  $connection->close();
} catch (Exception $exception) {
  throw $exception;
}

?>
