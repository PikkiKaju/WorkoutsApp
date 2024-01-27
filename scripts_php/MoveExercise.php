
<?php

  session_start();
  require_once "../source/connect.php";
  $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

  $moved_exercise_id = $_POST["moved_exercise_id"];
  $after_exercise_id = $_POST["after_exercise_id"];
  $workout_displayed = $_SESSION["workout_displayed"];
  $number_of_exercises = $_SESSION["number_of_exercises"];

  $My_SQL_query_remove_moved_id = "UPDATE `$workout_displayed` SET ExerciseID = 0 WHERE ExerciseID = $moved_exercise_id";
  if(@$connection->query($My_SQL_query_remove_moved_id)){
    if($moved_exercise_id > $after_exercise_id) {
      for($i = $moved_exercise_id; $i > $after_exercise_id; $i--) {
        $My_SQL_query_update_after_moved_ids = "UPDATE $workout_displayed SET ExerciseID = $i WHERE ExerciseID = $i-1";
        if(@$connection->query($My_SQL_query_update_after_moved_ids)) {
          echo "Updated ID: ". strval($i-1) ." to: ". strval($i) ." succesfully"."<br />";
        } else {
          echo $connection->error." on line: ".__LINE__."<br />";
        }
      }

      $My_SQL_query_update_moved_id = "UPDATE `$workout_displayed` SET ExerciseID = $after_exercise_id WHERE ExerciseID = 0";
      if(@$connection->query($My_SQL_query_update_moved_id)){
        echo "Updated ID: ".$moved_exercise_id." to: ".$after_exercise_id." succesfully"."<br />";
      } else {
        echo $connection->error." on line: ".__LINE__."<br />";
      }
    } else {
      for($i = $moved_exercise_id; $i < $after_exercise_id-1; $i++) {
        $My_SQL_query_update_after_moved_ids = "UPDATE `$workout_displayed` SET ExerciseID = $i WHERE ExerciseID = $i+1";
        if(@$connection->query($My_SQL_query_update_after_moved_ids)) {
          echo "Updated ID: ". strval($i+1) ." to: ". strval($i) ." succesfully"."<br />";
        } else {
          echo $connection->error." on line: ".__LINE__."<br />";
        }
      }

      $My_SQL_query_update_moved_id = "UPDATE `$workout_displayed` SET ExerciseID = $after_exercise_id-1 WHERE ExerciseID = 0";
      if(@$connection->query($My_SQL_query_update_moved_id)){
        echo "Updated ID: ".$moved_exercise_id." to: ".$after_exercise_id." succesfully"."<br />";
      } else {
        echo $connection->error." on line: ".__LINE__."<br />";
      }
    }
  } else {
    echo $connection->error." on line: ".__LINE__."<br />";
  }
?>
