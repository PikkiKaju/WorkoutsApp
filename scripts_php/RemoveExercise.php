
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

        $MySQL_query_remove_exercise = "DELETE FROM `$workout_displayed` WHERE ExerciseID = $exercise_id";
        if($connection->query($MySQL_query_remove_exercise)) {

        } else {
          echo "Could not send query to database: MySQL_query_remove_exercise"."<br />";
          echo "<strong>".$connection->error."</strong>"."<br /><br />";
        }

        $MySQL_query_select_all = "SELECT ExerciseID FROM `$workout_displayed`";
        if($result = $connection->query($MySQL_query_select_all)) {
          $num_of_rows = $result->num_rows + 1;
          if($num_of_rows > 0) {
            for($i = $exercise_id; $i < $num_of_rows; $i++) {
              $i_2 = $i + 1;
              $MySQL_query_update_ids = "UPDATE `$workout_displayed` SET ExerciseID = $i WHERE ExerciseID = $i_2 ";
              if(!$connection->query($MySQL_query_update_ids)) {
                echo "Could not send query to database: MySQL_query_update_ids"."<br />";
                echo "<strong>".$connection->error."</strong>"."<br /><br />";
              }
            }
          }
          $result->close();
        } else {
          echo "Could not send query to database: MySQL_query_select_all"."<br />";
          echo "<strong>".$connection->error."</strong>"."<br /><br />";
        }

        $MySQL_query_update_list = "UPDATE $db_workout_list_name SET NumberOfExercises = $num_of_rows WHERE WorkoutTableName = '$workout_displayed' ";
        if($connection->query($MySQL_query_update_list)) {

        } else {
          echo "Could not send query to database: MySQL_query_update_list"."<br />";
          echo $connection->error."<br />";
        }

      } else {
        echo ("Ajax data was not received.");
      }
    }
    unset($_POST['remove_exercise_id']);

  } else {
    echo ("SESSION array element 'workout_displayed' is not set ");
  }

$connection->close();
?>
