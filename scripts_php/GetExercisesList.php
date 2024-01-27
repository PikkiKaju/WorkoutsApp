<?php

require_once "../source/connect.php";

try {
  if ($connection = new mysqli($db_host, $db_user, $db_password, $db_name)) {
    $MySQL_query_get_exercises = $connection->prepare("SELECT ExerciseName FROM $db_exercises_list_name ORDER BY ExerciseShowPosition ASC");

    if ($MySQL_query_get_exercises->execute()) {
      $result = $MySQL_query_get_exercises->get_result();
      $json[] = array();
      while ($row = $result->fetch_assoc()) {
        $json[] = $row;
      }
      echo json_encode($json);
      $MySQL_query_get_exercises->close();
    } else {
      throw new Exception("Could not get exercises", 1);
    }
  } else {
    throw new Exception("Unable to connect", 1);
    if ($connection->connect_errno) {
      throw "Error: ".$connection->connect_errno." - ".$connection->connect_error;
    }
  }
  $connection->close();
} catch (Exception $exception) {
  throw $exception;
}

?>