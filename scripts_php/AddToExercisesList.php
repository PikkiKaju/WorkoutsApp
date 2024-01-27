<?php

function addToExercisesList($connection, $db_exercises_list_name) {
  try {
    if ($connection) {
      if(isset($_POST["exercise_name"])) {
        $exercise_name = $_POST["exercise_name"];
        $exercise_did_occur = false;
        $smallest_id = 1;
        $MySQL_query_get_exercises = "SELECT * FROM $db_exercises_list_name ORDER BY ExerciseID ASC";

        if ($result = $connection->query($MySQL_query_get_exercises)) {
          while ($row = $result->fetch_assoc()) {
            if ($row["ExerciseID"] == $smallest_id) $smallest_id++;
            if ($row["ExerciseName"] == $exercise_name) {
              $exercise_did_occur = true;
              break;
            }
          }
        } else {
          throw new Exception("Could not get exercises", 1);
        }
        
        $MySQL_query_add_exercise = $connection->prepare("INSERT INTO $db_exercises_list_name (ExerciseID,	ExerciseName, ExerciseShowPosition,	ExercisePopularity) VALUES (?,?,0,0) ");
        $MySQL_query_add_exercise->bind_param('is', $smallest_id, $exercise_name);
        
        if (!$exercise_did_occur) {
          try {
            $MySQL_query_add_exercise->execute();
          } catch (Exception $exception) {
            throw $exception;
          }
        }
        unset($_POST['exercise_name']);
      } else {
        throw new Exception("Exercise name not set", 1);
      }
    } else {
      throw new Exception("Unable to connect", 1);
      if ($connection->connect_errno) {
        echo "Error: ".$connection->connect_errno." - ".$connection->connect_error;
      }
    }
  } catch (Exception $exception) {
    throw $exception;
  }
}

?>