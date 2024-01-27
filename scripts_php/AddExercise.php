<?php

session_start();
require_once "../source/connect.php";
require_once "AddToExercisesList.php";

$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

try {
  if ($connection && isset($_POST['exercise_name'])) {
    $workout_displayed = $_SESSION['workout_displayed'];
    $exercise_name = $_POST['exercise_name'];
    $weights = "";
    $number_of_repetitions = "";
    $i = 1;
    while (isset($_POST['repetitions_'.$i])) {
      $weights = $weights."_".$_POST['weights_'.$i];
      $number_of_repetitions = $number_of_repetitions."_".$_POST['repetitions_'.$i];
      $i++;
    }
    $i--;
    if($num_result = $connection->query("SELECT * FROM $workout_displayed")) {
      $number_of_exercises = $num_result->num_rows + 1;
    }

    $MySQL_query_insert_exercise = $connection->prepare(" INSERT INTO `$workout_displayed` (ExerciseID, ExerciseName, Weights, NumberOfRepetitions) VALUES (?,?,?,?)");
    $MySQL_query_insert_exercise->bind_param('isss', $number_of_exercises, $exercise_name, $weights, $number_of_repetitions);

    if($MySQL_query_insert_exercise->execute())
    {
      $_SESSION['exercise_added'] = TRUE;
      addToExercisesList($connection, $db_exercises_list_name);
      echo "Exercise inserted succesfully."."<br />";
    } else {
      echo "<strong>".$connection->error."</strong>"."<br /><br />";
    }

    $MySQL_query_update_list = " UPDATE $db_workout_list_name SET NumberOfExercises = '$number_of_exercises' WHERE WorkoutTableName = '$workout_displayed' ";
    if($connection->query($MySQL_query_update_list)) {
      echo "Number of excercises in workout_list succesfully updated."."<br />";
    } else {
      echo "Could not send query."."<br />";
      echo "Could not update Number of excercises in workout list."."<br />";
      echo "<strong>".$connection->error."</strong>"."<br /><br />";
    }
    unset($_POST['exercise_name']);
    while($i != 0) {
      unset($_POST['weights_'.$i]);
      unset($_POST['repetitions_'.$i]);
      $i--;
    }
    header("Location: ../index.php");
  } else {
    throw new Exception("Unable to connect", 1);
    if ($connection->connect_errno) {
      echo "Error: ".$connection->connect_errno." - ".$connection->connect_error;
    }
  }
} catch (Exception $exception) {
  throw $exception;
  if ($connection->connect_errno > 0) {
    echo $connection->connect_error;
  }
}

?>
