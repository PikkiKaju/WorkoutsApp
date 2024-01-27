<?php

session_start();
require_once "../source/connect.php";
$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0)
{
  echo "Error: ".$connection->connect_errno."Description: ".$connection->connect_error;
}
else
{
  if (isset($_POST["new_workout_date"]))
  {
    $new_date = $_POST["new_workout_date"];
    $current_table_name = $_SESSION["workout_displayed"];
    $workout_year = substr($new_date, 0, 4);
    $workout_month = substr($new_date, 5, 2);
    $workout_day = substr($new_date, 8, 2);
    $new_table_name = strtok($current_table_name, "_")."_"
      .$workout_month."_".$workout_day."_".$workout_year;
    

    $MySQL_query_redate_table = "ALTER TABLE `$current_table_name` RENAME TO `$new_table_name`";
    $MySQL_query_redate_in_list = "UPDATE $db_workout_list_name SET WorkoutTableName='$new_table_name', WorkoutDate='$new_date' WHERE WorkoutTableName = '$current_table_name'";

    if ($result_table = $connection->query($MySQL_query_redate_table)) {
      if ($result_list = $connection->query($MySQL_query_redate_in_list)) {
        echo "Workout has been renamed.";
        $_SESSION["workout_displayed"] = $new_table_name;
        unset($_POST["new_workout_date"]);
        unset($_POST["current_workout_name"]);
      }
      else {
        echo "Workout's date could not be changed in _workout_list.";
      }
    } else {
      echo "Workout table could not be renamed id database.";
    }
  }
}

?>
