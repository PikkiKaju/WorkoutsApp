
<?php

session_start();
require_once "../source/connect.php";
$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if(isset($_SESSION['workout_displayed'])) {
  $workout_displayed = $_SESSION['workout_displayed'];

  if ($connection->connect_errno > 0) {
    echo $connection->connect_error."<br />";
  } else {
    if(isset($_POST['remove_exercise_id'])) {
      $exercise_id = $_POST['remove_exercise_id'];
      $series_id = $_POST['remove_series_id'];
      $series_repetitions = $_POST["remove_series_repetitions"];
      $series_weights = $_POST["remove_series_weights"];

      $MySQL_query_workout_data = "SELECT * FROM `$workout_displayed` WHERE `ExerciseID` = $exercise_id";

      if($display_result = $connection->query($MySQL_query_workout_data)) {
        $row = $display_result->fetch_assoc();
        $table_weights = $row["Weights"]; 
        $table_repetitions = $row["NumberOfRepetitions"]; 
        
        $occurances = 0;
        $position = 0;
        for ($i = 0; $i < strlen($table_repetitions); $i++) {
          if ($table_repetitions[$i] == "_") {
            $occurances++;
          }
          if ($occurances == $series_id) break;
          $position++;
        }

        $occurances = 0;
        $position = 0;
        for ($i = 0; $i < strlen($table_weights); $i++) {
          if ($table_weights[$i] == "_") {
            $occurances++;
          }
          if ($occurances == $series_id) break;
          $position++;
        }

        $table_weights = substr_replace($table_weights, "", $position, strlen($series_weights)+1);
        $table_repetitions = substr_replace($table_repetitions, "", $position, strlen($series_repetitions)+1);
        
        $display_result->close();
      } else {
        echo "Could not get data."."<br />"; 
      }

      $MySQL_query_remove_series = "UPDATE `$workout_displayed` SET `Weights` = '$table_weights', `NumberOfRepetitions` = '$table_repetitions' WHERE `ExerciseID` = $exercise_id";
      
      if($connection->query($MySQL_query_remove_series)) {
        echo "Series removed."."<br />";
      } else {
        echo "Could not send query: MySQL_query_remove_exercise"."<br />";
        echo "<strong>".$connection->error."</strong>"."<br /><br />";
      }

      unset($_POST['remove_exercise_id']);
    } else {
      echo "Ajax data was not received."."<br />";
    }
  }
} else {
  echo ("SESSION array element 'workout_displayed' is not set ");
}

$connection->close();
?>
