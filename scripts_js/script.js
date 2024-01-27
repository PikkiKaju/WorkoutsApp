import { darkmodeToggle } from "./darkmode.js";
import { panelHandler } from "./panelHandlers.js";
import { addNewWorkoutWindowHandler } from "./newWorkoutWindow.js";
import { draggablesHandler, containerDragoverHandler } from "./dragAndDropHandler.js";
import { onWorkoutNameFocusHandler, onWorkoutDateFocusHandler } from "./workoutHeaderInputs.js";
import { tableAreaDropdownToggle } from "./tableAreaDropdown.js";
import { seriesDropdownHandler } from "./seriesDropdown.js";
import { removeExercise } from "./removeExercise.js";
import { removeSeries } from "./removeSeries.js";
import "./addExerciseForm.js";

//Image directory
var edit_image_name_src = "source/images/edit_name_light.png";
if (getCookie("darkmodeToggled")) {
  edit_image_name_src = "source/images/edit_name_dark.png";
}

window.onload = function () {
  
  panelHandler()
  addNewWorkoutWindowHandler()

  //Drag and Drop
  let container = document.querySelector("#workout-table-body");
  let draggables = document.querySelectorAll(".table-row");
  let neighbourItems = {
    lastAfterItemId: 0,
    lastMovedItemId: 0
  };

  if (draggables.length > 1) { draggablesHandler(draggables, neighbourItems); }
  if (container) { containerDragoverHandler(container, neighbourItems); }

  //Changing workout's name
  if (document.getElementById("table-area-header")) {
    let name_input = document.getElementById("table-area-workout-name");
    const end = name_input.value.length;
    name_input.setSelectionRange(end, end);
    name_input.addEventListener("focus", onWorkoutNameFocusHandler);
    
    document.getElementById("table-area-name").onclick = () => {
      name_input.focus();
    }
    document.getElementById("table-area-menu-rename").onclick = () => {
      name_input.focus();
      $("#table-area-dropdown-menu").toggle();
    }  
  }
  //Changing workout's date
  if (document.getElementById("table-area-header")) {
    let date_input = document.getElementById("table-area-workout-date");
    document.getElementById("table-area-workout-date").onclick = () => {
      date_input.focus();
    }
    document.getElementById("table-area-menu-redate").onclick = () => {
      date_input.focus();
      $("#table-area-dropdown-menu").toggle();
    }
  
    date_input.addEventListener("focus", onWorkoutDateFocusHandler);
  }

  //Table area -- Workout's dropdown menu
  let focusHandlerListenerAdded = { value: false };
  if (document.getElementById("table-area-button")) {
    document.getElementById("table-prompt-div").style.display = "none";
    document.getElementById("table-area-button").onclick = () => {
      tableAreaDropdownToggle(focusHandlerListenerAdded);
    }
  }

  //Table area -- Series dropdown
  let buttons = [...document.getElementsByClassName("table-series-dropdown-button")];

  buttons.forEach(button => {
    button.addEventListener("click", seriesDropdownHandler);
  });

  //Changing Table area padding accoridng to its width
  const table_area = document.getElementById("table-area");
  const table_padding = table_area.style.padding;

  const resize_observer = new ResizeObserver(function (entries) {
    let element = entries[0].target;
    let width = element.offsetWidth;
    if (width < 800) {
      element.style.padding = "0 40px 0 20px";
    } else {
      element.style.padding = table_padding;
    }
  });
  resize_observer.observe(table_area);

  //Submiting workout_name for php script to show the table
  let formElements = document.querySelectorAll(".form-workout");
  formElements.forEach(formElement => {
    formElement.addEventListener("click", event => {
      for (let i = 0; i < event.composedPath().length; i++) {
        if (event.composedPath()[i].getAttribute("class") == ("form-workout")) {
          event.composedPath()[i].submit(); break;
        }
      }
    });
  });

  //Toggling darkmode after clicking darkmode switch
  darkmodeToggle(edit_image_name_src);
  document.querySelector("#darkmode-switch").addEventListener("click", () => {
    let darkmodeCookie = getCookie("darkmodeToggled");
    if (darkmodeCookie != null && darkmodeCookie != "") {
      if (darkmodeCookie == "TRUE") {
        setCookie("darkmodeToggled", "FALSE");
      } else {
        setCookie("darkmodeToggled", "TRUE");
      }
      darkmodeToggle(edit_image_name_src);
    }
  });

  // Removing exercise from data base and Table
  let removeExerciseButtons = document.querySelectorAll(".remove-exercise-button");
  removeExerciseButtons.forEach(button => {
    button.addEventListener("click", removeExercise);
  });

  // Removing series from data base and Table
  let removeExerciseSeriesButtons = document.querySelectorAll(".remove-exercise-series-button");
  removeExerciseSeriesButtons.forEach(button => {
    button.addEventListener("click", removeSeries);
  });
}

//Hiding no-exercise-label text after adding exercise to the Table
$(function () {
  if ((document.getElementsByClassName('table-row')).length > 0) {
    document.getElementById("no-exercises-label").innerHTML = "";
  }
});
