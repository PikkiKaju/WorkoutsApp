<?php

function getCookies() {
  if (isset($_COOKIE["darkmodeToggled"])) {
    if (htmlspecialchars($_COOKIE["darkmodeToggled"]) == "TRUE") {
      $_SESSION['darkmode_toggled'] = TRUE;
    } else {
      $_SESSION['darkmode_toggled'] = FALSE;
    }
  } else {
    $_SESSION['darkmode_toggled'] = FALSE;
  }
  if (isset($_COOKIE["panelHidden"])) {
    if (htmlspecialchars($_COOKIE["panelHidden"]) == "TRUE") {
      $_SESSION['panel_hidden'] = TRUE;
    } else {
      $_SESSION['panel_hidden'] = FALSE;
    }
  } else {
    $_SESSION['panel_hidden'] = FALSE;
  }
}

// checking if any workouts are added and showing Prompt to add one if there aren't any
function isWorkoutListEmpty() {
  global $connection;
  global $db_workout_list_name;
  $MySQL_query_list_exist = " SELECT * FROM  $db_workout_list_name ";
  if($result = $connection->query($MySQL_query_list_exist)) {
    $number_of_workouts = $result->num_rows;
    if($number_of_workouts == 0) {
      $answer = True;
    } else {
      $answer = False;
    }
    unset($number_of_workouts);
    $result->close();
    return $answer;
  }
}
// str_contains for php earlier than php 8
if (!function_exists('str_contains')) {
  function str_contains($haystack, $needle) {
      return $needle !== '' && mb_strpos($haystack, $needle) !== false;
  }
}

function consoleLog($expresion) {
  echo <<< HTML
    <script>
      console.log("$expresion");
    </script>
  HTML;
}


?>
