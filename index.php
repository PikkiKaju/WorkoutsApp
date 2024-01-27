<?php
  session_start();
  require_once "source/connect.php";
  require_once "scripts_php/lib.php";

  $connection = new mysqli($db_host, $db_user, $db_password, $db_name);
  $_SESSION['mysqli_object'] = $connection;

  getCookies();
  $darkmode_toggled = $_SESSION['darkmode_toggled'];
  $panel_hidden = $_SESSION['panel_hidden'];

  //Class for turning on darkmode on window load
  if ($darkmode_toggled) {
    $darkmode_class = 'class="darkmode" ';
  } else {
    $darkmode_class = ' ';
  }

  //Classes for hiding panel on window load
  if ($panel_hidden) {
    $cookie_panel_wrap = 'style="width: 0px;" ';
    $cookie_panel = 'style="left: -400px;" ';
    $cookie_panel_button = 'style="left: 0px;" ';
  } else {
    $cookie_panel_wrap = 'style="width: 400px;" ';
    $cookie_panel = 'style="left: 0px;" ';
    $cookie_panel_button = 'style="left: 400px;" ';
  }
  //Checking if provided earlier workout name was valid
  if(isset($_SESSION["name_valid"])) {
    $invalid_name = "<p>Can't use:  <  or  ></p>";
    unset($_SESSION["name_valid"]);
  } else {
    $invalid_name = "";
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="stylesheets/style.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comfortaa"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto"/>
    <script type="text/javascript" src="scripts_js/jquery.js"></script>
    <script type="text/javascript" src="scripts_js/jquery.js"></script>
    <script type="text/javascript" src="scripts_js/utilities.js"></script>
    <script type="module" src="scripts_js/script.js"></script>
    <title>Workouts app</title>
  </head>

  <body id="body" <?= $darkmode_class ?> >
    <header>
      <div id="workout-label">
        Workouts app
      </div>
      <?php if(isWorkoutListEmpty()) { 
        echo<<<HTML
        <div id="toggle-window-label-div">
          <div id="toggle-window-label">Add one here!</div>
        </div>
        HTML; 
      } ?>
      <div id="button-div">
        <div id="darkmode-switch-div">
          <div id="darkmode-switch" class="darkmode-switch">
            <div id="darkmode-switch-node" class="darkmode-switch-node"></div>
          </div>
        </div>
      </div>
    </header>   
    <div id="page-wrap">
      <div id="panel-wrap" <?= $cookie_panel_wrap ?>>
        <div id="panel" <?= $cookie_panel.$darkmode_class ?>>
          <button type="button" id="toggle-window-button">
            <div id="button-cross-vert" class="button-cross"></div>
            <div id="button-cross-hori" class="button-cross"></div>
          </button>
          <div id="add-new-workout-window-wrap">
            <div id="add-new-workout-window">
              <form id="add-workout-form" action="scripts_php/AddWorkout.php" method="post">
                <input type="text" name="workout_name" class="add-workout-input" id="add-workout-name" autocomplete="off" value="" pattern="[^<>]*" required placeholder="Workout name" />
                <input type="date" name="workout_date" class="add-workout-input" id="add-workout-date" min="1900-01-01" max="2999-12-31" required />
                <div>
                  <div><?= $invalid_name ?></div>
                  <input type="submit" value="Add" class="add-workout-input" id="add-workout-submit" />
                </div>
              </form>
            </div>
          </div>
          <?php if (isset($_SESSION['table_created'])) { 
            echo<<<HTML
            <div id="workout-added">
              <p>New workout has been added!</p>
            </div>
            HTML; 
            unset($_SESSION['table_created']); 
          } ?>
          <div id="workout-list" >
            <?php
            if ($connection->connect_errno != 0)
            {
              echo "Error: ".$connection->connect_errno."Description: ".$connection->connect_error;
            }
            else {
              $MySQL_query_read_workout = " SELECT * FROM $db_workout_list_name ORDER BY `_workout_list`.`WorkoutDate` DESC";
              if($result = $connection->query($MySQL_query_read_workout))
              {
                $number_of_workouts = $result->num_rows;
                if($number_of_workouts > 0)
                {
                  $current_month = "";
                  $current_year = "";
                  echo<<<HTML
                    <div>
                      <div>
                  HTML;
                  while ($row = $result->fetch_assoc())
                  {
                    $workout_table_name = $row['WorkoutTableName'];
                    $workout_id = $row['WorkoutID'];
                    $workout_date = $row['WorkoutDate'];
                    $workout_year = substr($workout_date, 0, 4);
                    $workout_month = substr($workout_date, 5, 2);
                    $workout_day = substr($workout_date, 8, 2);
                    $workout_date = $workout_month."-".$workout_day."-".$workout_year;
                    $workout_name = $row['WorkoutName'];
                    $workout_date_html = $workout_month."/".$workout_day."/".$workout_year;
                    $workout_name_html = str_replace(" ","_",$workout_name);
                    $number_of_exercises = $row['NumberOfExercises'];
                    $workout_description = $row['WorkoutDescription'];

                    $workout_div_background = "workout-div-not-displayed";
                    $workout_div_background_darkmode = "";
                    $workout_div_darkmode = "";
                    if (isset($_SESSION['workout_displayed'])) {
                      if($_SESSION['workout_displayed'] == $workout_table_name) {
                        if($darkmode_toggled) {
                          $workout_div_background_darkmode = "darkmode-workout-form";
                          $workout_div_background = "workout-div-displayed";
                          $workout_div_darkmode = "darkmode";
                        } else { $workout_div_background = "workout-div-displayed"; }
                      } else {
                        if($darkmode_toggled) { $workout_div_darkmode = "darkmode"; }
                      }
                    }
                    if ($current_year != $workout_year) {
                      $current_year = $workout_year;
                      echo<<<HTML
                        </div>
                      </div>
                      <div class="workout-list-year-wrap" $darkmode_class id="workout-list-$current_year">
                        <div class="year-wrap-header">
                          <div class="year-wrap-arrows" id="year-wrap-arrows-$current_year">
                            <div class="year-wrap-arrow-left year-wrap-arrow" $darkmode_class></div>
                            <div class="year-wrap-arrow-right year-wrap-arrow" $darkmode_class></div>
                          </div>
                          <p class="workout-list-year">$current_year</p>
                        </div>
                        <div class="year-wrap-list" id="year-wrap-$current_year">
                      HTML;
                    }
                    if ($current_month != intval($workout_month)) {
                      $current_month = intval($workout_month);
                      $month_name;
                      switch ($current_month) {
                        case 1:
                          $month_name = "January";
                          break;
                        case 2:
                          $month_name = "February";
                          break;
                        case 3:
                          $month_name = "March";
                          break;
                        case 4:
                          $month_name = "April";
                          break;
                        case 5:
                          $month_name = "May";
                          break;
                        case 6:
                          $month_name = "June";
                          break;
                        case 7:
                          $month_name = "July";
                          break;
                        case 8:
                          $month_name = "August";
                          break;
                        case 9:
                          $month_name = "September";
                          break;
                        case 10:
                          $month_name = "October";
                          break;
                        case 11:
                          $month_name = "November";
                          break;
                        case 12:
                          $month_name = "December";
                          break;
                      }
                      echo<<<HTML
                      <div>
                        <p class="workout-list-month">$month_name</p>
                      </div>
                      HTML;
                    }
                    echo<<<HTML
                    <form id="form-$workout_name_html" class="form-workout" action="scripts_php/DisplayWorkout.php" method="post">
                      <div class="workout-div-anchor" id="form-div-$workout_name_html">
                        <input id="form-input-$workout_name_html" name="display_workout_name" value="$workout_table_name" style="display: none;" />
                        <div class="workout-div $workout_div_background_darkmode $workout_div_background $workout_div_darkmode">
                          <div class="workout-div-name">
                            <p>$workout_name</p>
                          </div>
                          <div class="workout-div-date">
                            <p>$workout_date_html</p>
                          </div>
                        </div>
                      </div>
                    </form>
                    HTML;
                  }
                  echo<<<HTML
                    </div>
                  </div>
                  HTML;
                } else {
                  echo<<<HTML
                  <div class="workout-list-empty">
                    <p>No workouts added yet.</p>
                  </div>
                  HTML;
                }
                unset($number_of_workouts);
                $result->close();
              }
            }
            ?>
          </div>
        </div>
        <div id="panel-hide-button" <?= $cookie_panel_button ?>>
          <div id="panel-hide-arrow-top" class="panel-hide-arrow"></div>
          <div id="panel-hide-arrow-bot" class="panel-hide-arrow"></div>
        </div>
      </div>
      <div id="table-area">
        
        <?php
        if (isset($_SESSION['workout_displayed']))
        {
          $workout_displayed = $_SESSION['workout_displayed'];
          $MySQL_query_workout_data = " SELECT * FROM `$workout_displayed`";
          $MySQL_query_from_list = " SELECT WorkoutDate, WorkoutName  FROM $db_workout_list_name WHERE WorkoutTableName = '$workout_displayed' ";

          if($display_result = @$connection->query($MySQL_query_workout_data))
          {
            if($display_from_list = @$connection->query($MySQL_query_from_list))
            {
              $workouts_name_date = $display_from_list->fetch_assoc();
              $name = $workouts_name_date['WorkoutName'];
              $date = $workouts_name_date['WorkoutDate'];
              $edit_image_name_src = "source/images/edit_name_light.png";
              if ($darkmode_toggled) {
                $edit_image_name_src = "source/images/edit_name_dark.png";
              }
              echo<<<HTML
                <div id="table-area-header" class="table-area-header">
                  <div id="table-area-name">
                    <div id="table-area-name-wrap">
                      <label for="table-area-workout-name">
                        <img id="table-area-name-image" src="$edit_image_name_src" />
                      </label>
                      <input id="table-area-workout-name" $darkmode_class type="text" name="workout-name" value="$name" spellcheck="false" />
                    </div>
                    <div id="table-area-name-prompt">
                      <p>Name changed</p>
                    </div>
                  </div>
                  <div id="table-area-date">
                    <div>
                      <input id="table-area-workout-date" $darkmode_class type="date" name="workout-date" value="$date" spellcheck="false" min="1900-01-01" max="2999-12-31" />
                    </div>
                    <div class="header-area-save-button">
                      <button id="header-area-date-button">Save</button>
                    </div>
                    <div id="table-area-date-prompt">
                      <p>Date changed</p>
                    </div>
                  </div>
                  <div id="table-area-dropdown">
                    <div id="table-area-button">
                      <div class="table-area-stripe"></div>
                      <div class="table-area-stripe"></div>
                      <div class="table-area-stripe"></div>
                    </div>
                    <div id="table-area-dropdown-menu">
                      <ul>
                        <li id="table-area-menu-rename">Rename</li>
                        <li id="table-area-menu-redate">Change date</li>
                        <li id="table-area-menu-edit">Edit description</li>
                        <hr>
                        <li id="table-area-menu-delete">Delete</li>
                      </ul>
                    </div>
                    <div id="table-prompt-div">
                      <div id="table-delete-prompt-box" class="table-prompt-box">
                        <div class="table-prompt-box-text">
                          <p>Delete $name ?</p>
                        </div>
                        <div class="table-prompt-box-buttons">
                          <button id="table-prompt-button-yes" class="table-prompt-box-button">Yes</button>
                          <button id="table-prompt-button-no" class="table-prompt-box-button">No</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              HTML;
              $number_of_exercises = $display_result->num_rows;
              $_SESSION["number_of_exercises"] = $number_of_exercises;
              if($number_of_exercises > 0)
              {
                echo<<<HTML
                <table id="workout-table" class="workout-table">
                  <thead class="workout-table-head">
                    <tr>
                      <th class="table-element-move">
                        <div></div>
                      </th>
                      <th class="table-element-id">
                        <div><div>
                      </th>
                      <th class="table-element-name">Exercise name</th>
                      <th class="table-element-arrows">
                        <div></div>
                      </th>
                      <th class="table-element-repetitions">Series</th>
                      <th class="table-element-weights">Weights</th>
                      <th class="table-element-remove"><br /></th>
                    </tr>
                  </thead>

                  <tbody id="workout-table-body" class="workout-table-body">
                HTML;

                while($row = $display_result->fetch_assoc())
                {
                  $exercise_id = $row['ExerciseID'];
                  $exercise_name = $row['ExerciseName'];
                  $number_of_repetitions_arr = explode("_", $row['NumberOfRepetitions']);
                  array_splice($number_of_repetitions_arr, 0, 1);
                  $weights_arr = explode("_", $row['Weights']);
                  array_splice($weights_arr, 0, 1);
                  $number_of_series = count($number_of_repetitions_arr);
                  
                  if(str_replace("_None", "", $row['Weights']) == "") {
                    $weights_range = "None";
                    $weights = "None";
                  } else {
                    $weights_range = min($weights_arr)." - ".max($weights_arr);
                    $weights = str_replace("_", " ", $row['Weights']);
                  }
                  $number_of_repetitions = str_replace("_", " ", $row['NumberOfRepetitions']);

                  echo<<<HTML
                    <tr class="table-row" id="table-row-$exercise_id" >
                      <td colspan="7">
                        <table id="exercise-table-$exercise_id" class="exercise-table">
                          <thead class="exercise-table-head">
                            <tr colspan="7">
                              <th class="table-element-move" draggable="true">
                                <div>
                                  <div></div>
                                  <div></div>
                                </div>
                              </th>
                              <th class="table-element-id">
                                <div>$exercise_id</div>
                              </th>
                              <th class="table-element-name">$exercise_name</th>
                              <th class="table-element-arrows">
                                <button class="table-series-dropdown-button">
                                  <div class="table-element-arrow-left table-element-arrow"></div>
                                  <div class="table-element-arrow-right table-element-arrow"></div>
                                </button>
                              </th>
                              <th class="table-element-repetitions">$number_of_series</th>
                              <th class="table-element-weights">$weights_range</th>
                              <th class="table-element-remove">
                                <button type="button" class="remove-exercise-button remove-button-cross" id="remove-exercise-button-$exercise_id">
                                  <div class="button-cross remove-button-cross-top"></div>
                                  <div class="button-cross remove-button-cross-bot"></div>
                                </button>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="exercise-table-body">
                  HTML;
                  foreach ($weights_arr as $key => $value) {
                    $weight = $value;
                    $repetition = $number_of_repetitions_arr[$key];
                    echo<<<HTML
                    <tr series-number="$key">
                      <td></td>
                      <td></td>
                    HTML;
                    if ($key == 0) {
                      echo<<<HTML
                      <td column="description" class="exercise-series-decription" rowspan="0">
                        <p> rloerm afnsfsna isaf ba fdsakj</p>
                      </td>
                      HTML;
                    }
                    echo<<<HTML
                      <td column="track">
                        <div class="exercise-series-track">
                          <div class="exercise-series-track-ver"></div>
                          <div class="exercise-series-track-hor"></div>
                        </div>
                      </td>
                      <td column="repetitions">$repetition</td>
                      <td column="weights">$weight</td>
                      <td column="remove-button">
                        <button type="button" class="remove-exercise-series-button remove-button-cross" id="remove-exercise-series-button-$exercise_id">
                          <div class="button-cross remove-button-cross-top"></div>
                          <div class="button-cross remove-button-cross-bot"></div>
                    </button>
                      </td>
                    </tr>
                    HTML;
                  }
                  echo<<<HTML
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  HTML;
                } 
                echo<<<HTML
                  </tbody>
                </table>
                HTML;
              }

              echo "<div id='no-exercises-label-div'>";
              if($number_of_exercises == 0) {
                echo "<h3 id='no-exercises-label'>No exercises added yet.</h3>";
              } else {
                echo "<h3 id='no-exercises-label'></h3>";
              }
              echo<<<HTML
              </div>
                <div id="add-exercise-div">
                  <div id="add-exercise-button-div">
                    <button id="add-exercise-button" >
                      <p>Add Exercise</p>
                      <div id="add-exercise-arrows">
                        <div id="add-exercise-arrow-left" class="add-exercise-arrow"></div>
                        <div id="add-exercise-arrow-right" class="add-exercise-arrow"></div>
                      </div>
                    </button>
                  </div>
                  <div id="add-exercise-form-div">
                    <form id="add-exercise-form" style="display: none;" action="scripts_php/AddExercise.php" method="post">
                      <input type="text" name="exercise_name" id="add-exercise-form-name" class="add-exercise-form-input" value="" placeholder="Exercise name" required autocomplete="off"/>
                      <div id="add-exercise-list"></div>
                      <input type="text" name="exercise_description" id="add-exercise-form-description" class="add-exercise-form-input" value="" placeholder="Description" autocomplete="off"/>
                      <div id="series-labels">
                        <p class=""></p>
                        <p>Number of repetitions</p>
                        <br />
                        <p>Weights</p>
                      </div>
                      <div id="series-div">
                        <div id="exercise-series-1" class="single-series-div">
                          <p class="series-id-label">1.</p>
                          <input id="add-exercise-form-repetitions-1" class="add-exercise-form-input" type="text" name="repetitions_1" pattern="[0-9]{1,}" title="Must be a number" value="" placeholder="Number of repetitions" autocomplete="off" required/>
                          <br />
                          <input id="add-exercise-form-weights-1" class="add-exercise-form-input" type="text" name="weights_1" pattern="[0-9]{1,}" title="Must be a number" value="" placeholder="None" autocomplete="off"/>
                          <p class="series-weights-label">kg</p>
                        </div>
                      </div>
                      <button type="button" id="add-series-button">Add series</button>
                      <br />
                      <div id="exercise-form-buttons-div">
                        <button type="submit" class="exercise-form-button" id="submit-exercise-button">Add</button>
                      </div>
                    </form>
                  </div>
                </div>
              HTML;
            }
            $display_result->close();
          } else {
            echo<<<HTML
            <div class="table-area-header">
              <p>Could not display a workout.</p>
            </div>
            HTML;
          }
        } else {
          echo<<<HTML
          <div class="table-area-header">
            <p>Not displaying any workout.</p>
          </div>
          HTML;
        }
        ?>
      </div>
    </div>
  </body>
</html>

<?php
  $connection->close();
?>
