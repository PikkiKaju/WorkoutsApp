
// Showing/hiding add-workout-window

export function addNewWorkoutWindowHandler() {
  let new_workout_window_visible = false;

  function toggleNewWorkoutWindow(event) {
    let add_window_wrap = document.getElementById("add-new-workout-window-wrap");
    let add_window = document.getElementById("add-new-workout-window");
    let button_label = document.getElementById("toggle-window-label");
    let input_name = document.getElementById("add-workout-name");
    let input_date = document.getElementById("add-workout-date");
    let delay_time = ((Number(getComputedStyle(add_window).transitionDuration.slice(0, -1))) * 1000);
    let cross_height = getComputedStyle(document.querySelector("#button-cross-hori")).width;

    function showNewWorkoutWindow() {
      document.getElementById('button-cross-vert').style.height = '0';
      if (button_label) {
        button_label.style.right = '-150px';
        let text_delay_time = Number((getComputedStyle(button_label).transitionDuration).slice(0, -1)) * 1000;
        setTimeout(() => {
          document.getElementById('toggle-window-label-div').style.display = 'none';
        }, text_delay_time);
      }

      add_window_wrap.style.display = "block";
      setTimeout(() => {
        add_window_wrap.style.opacity = '1';
        add_window_wrap.style.height = "185px";
        add_window.style.top = "0px";
      }, 1);

      let date = new Date();
      input_date.value = date.toISOString().substring(0, 10);
      input_name.focus();
      new_workout_window_visible = true;
    }
    function hideNewWorkoutWindow() {
      document.getElementById('button-cross-vert').style.height = cross_height;

      add_window_wrap.style.opacity = '0';
      add_window_wrap.style.height = "0px";
      add_window.style.top = "-120px";
      setTimeout(() => {
        add_window_wrap.style.display = "none";
      }, delay_time);

      if (button_label) {
        document.getElementById('toggle-window-label-div').style.display = 'block';
        setTimeout(() => {
          button_label.style.right = '0px';
        }, 0);
      }
      new_workout_window_visible = false;
    }
    if (new_workout_window_visible) hideNewWorkoutWindow();
    else showNewWorkoutWindow();
  }
  document.getElementById('toggle-window-button').addEventListener("click", toggleNewWorkoutWindow);
  if (document.getElementById('toggle-window-label-div')) {
    document.getElementById('toggle-window-label-div').onclick = () => toggleNewWorkoutWindow();
  }

  //Managing add-workout form inputs
  if (document.getElementById("add-new-workout-window")) {
    let name_input = document.getElementById("add-workout-name");
    let form = document.getElementById("add-workout-form");
    name_input.addEventListener("focus", focusHandler);
    function focusHandler() {
      name_input.addEventListener("keydown", keyDownHandler);
    }
    let prompt_div = form.children[2].children[0];
    function keyDownHandler({ key }) {
      let input_value = name_input.value;
      if (input_value.includes("<") || input_value.includes(">") || key === "<" || key === ">") {
        prompt_div.innerHTML = `
        <p><b>Can't use: \xa0 < \xa0  or \xa0 ><b></p>
        `;
      } else {
        prompt_div.innerHTML = "";
      }
    }
    if (prompt_div.children.length > 0) {
      toggleNewWorkoutWindow();
    }
  }

  // Fading out the workout-added banner
  if (document.getElementById('workout-added')) {
    function workoutAddedFadeOut() {
      document.getElementById('workout-added').style.opacity = '0';
    }
    function workoutAddedFadeOutDisplay() {
      document.getElementById('workout-added').style.display = 'none';
    }
    let delay_to_fade_time = 2000;
    let disappering_time = delay_to_fade_time + ((Number(getComputedStyle(document.querySelector("#workout-added")).transitionDuration.slice(0, -1))) * 1000);
    setTimeout(workoutAddedFadeOut, delay_to_fade_time);
    setTimeout(workoutAddedFadeOutDisplay, disappering_time);
  }
}