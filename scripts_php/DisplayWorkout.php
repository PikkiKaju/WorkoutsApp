<?php

session_start();

if (isset($_POST["display_workout_name"]))
{
  $_SESSION['workout_displayed'] = $_POST['display_workout_name'];
  echo "<p>".$_SESSION['workout_displayed']."</p>";
}

header('Location: ../index.php');

?>
