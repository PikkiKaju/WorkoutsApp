<?php

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
  echo "Error: ".$connection->connect_errno."Description: ".$connection->connect_error;
}
else
{
  if (isset($_POST["new_workout_name"]))  {
    $new_name = $_POST["new_workout_name"];
    $table_name = $_SESSION["workout_displayed"];

    if (str_contains($new_name,"<") || str_contains($new_name,">")) {
      $_SESSION["rename_valid"] = false;
      echo "Error: Workout name contains \"<\" or \">\""."<br>";
      die();
    } 

    $MySQL_query_rename_in_list = $connection->prepare(" UPDATE $db_workout_list_name SET WorkoutName = ? WHERE WorkoutTableName = ?");
    $MySQL_query_rename_in_list->bind_param("ss", $new_name, $table_name);
    if ($MySQL_query_rename_in_list->execute()) {
      echo "Workout has been renamed.";
      unset($_POST["new_workout_name"]);
    } else {
      echo "Workout could not be renamed in workout list.";
    }
  }
}
?>
