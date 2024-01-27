<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  session_start();
  require_once "../source/connect.php";
  $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

  if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
      return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    } 
  }
  
  if ($connection->connect_errno!=0)
  {
    echo "Error ".$connection->connect_errno.": ".$connection->error;
  }
  else
  {
    if (isset($_POST['workout_name']) && isset($_POST['workout_date'])) {

      $workout_name = $_POST['workout_name'];
      $workout_date = $_POST['workout_date'];
      $workout_year = substr($workout_date, 0, 4);
      $workout_month = substr($workout_date, 5, 2);
      $workout_day = substr($workout_date, 8, 2);

      if (str_contains($workout_name,"<") || str_contains($workout_name,">")) {
        $_SESSION["name_valid"] = false;
        echo "Error: Workout name contains \"<\" or \">\""."<br>";
        die(header('Location: ../index.php'));
      }

      $MyQSL_query_get_table_id = "SELECT WorkoutID FROM $db_workout_list_name ORDER BY WorkoutID ASC";
      $workout_ids = array();

      if ($result_ids = $connection->query($MyQSL_query_get_table_id)) {
        while ($row = $result_ids->fetch_assoc()) {
          array_push($workout_ids, intval($row['WorkoutID']));
        }
      }
      $smallest_id = 1;
      for ($i = 0; $i < count($workout_ids); $i++) {
        if ($workout_ids[$i] == $smallest_id) {
          $smallest_id++;
        } else {
          break;
        }
      }

      $table_name = $smallest_id."_".$workout_month."_".$workout_day."_".$workout_year;

      $MySQL_query_insert = $connection->prepare(" INSERT INTO $db_workout_list_name
        (WorkoutID, WorkoutTableName, WorkoutDate, WorkoutName, NumberOfExercises, WorkoutDescription)
        VALUES (?,?,?,?,0,'None') ");
      $MySQL_query_insert->bind_param('isss', $smallest_id, $table_name, $workout_date, $workout_name);
      $MySQL_query_create = $connection->prepare(" CREATE TABLE $table_name (
          ExerciseID INT NOT NULL AUTO_INCREMENT ,
          ExerciseName VARCHAR(50) NOT NULL ,
          Weights VARCHAR(20) NOT NULL ,
          NumberOfRepetitions VARCHAR(20) NOT NULL ,
          PRIMARY KEY (ExerciseID)
        )
        ENGINE = InnoDB
      ");

      if ($MySQL_query_insert->execute())
      {
        if ($MySQL_query_create->execute())
        {
          $_SESSION['table_created'] = TRUE;
          $_SESSION['workout_displayed'] = $table_name;
        } else {
          echo "Couldn't create table.<br>";
          echo "Error ".$connection->connect_errno.": ".$connection->error;
          $_SESSION['workout_displayed'] = "";
        }
      } else {
        echo "Couldn't insert record.<br>";
        echo "Error ".$connection->connect_errno.": ".$connection->error;
        $_SESSION['workout_displayed'] = "";
      }
    } else {
      echo "Submited data was not received.";
    }
    unset($_POST['workout_name']);
    unset($_POST['workout_date']);
    unset($workout_name);
    unset($workout_date);
    header('Location: ../index.php');
  }

?>
