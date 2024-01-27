
//Changing workout's name
export function onWorkoutNameFocusHandler() {
  let name_input = this;
  let current_workout_name = name_input.getAttribute("value");
  let new_workout_name = "";
  name_input.addEventListener("focusout", focusLossHandler);
  name_input.addEventListener("keydown", keyDownHandler);

  function focusLossHandler() {
    reverseValue();
    document.getElementById("table-area-name-prompt").style.opacity = "0";
  }
  function keyDownHandler({ key }) {
    new_workout_name = this.value;
    if (new_workout_name.includes("<") || new_workout_name.includes(">") || key === "<" || key === ">") {
      document.getElementById("table-area-name-prompt").children[0].innerHTML = `Name can't include: <  or  >`;
      document.getElementById("table-area-name-prompt").style.opacity = "1";
      document.getElementById("table-area-name-prompt").style.color = "red";
    } else {
      document.getElementById("table-area-name-prompt").style.opacity = "0";
      document.getElementById("table-area-name-prompt").style.color = "inherit";
      if (key === "Enter") {
        $.ajax({
          url: "./scripts_php/RenameWorkout.php",
          method: "POST",
          data: { "new_workout_name": new_workout_name }
        })
          .done(() => {
            document.getElementById("table-area-name-prompt").children[0].innerHTML = "Name changed";
            document.getElementById("table-area-name-prompt").style.opacity = "1";
            setTimeout(() => {
              document.getElementById("table-area-name-prompt").style.opacity = "0";
            }, 2000);
            changeValue();
            name_input.removeEventListener("focusout", focusLossHandler);
            name_input.removeEventListener("keydown", keyDownHandler);
            name_input.blur();
          })
          .fail(() => {
            document.getElementById("table-area-name-prompt").innerHTML = "Could not change name.";
            document.getElementById("table-area-name-prompt").style.opacity = "1";
            setTimeout(() => {
              document.getElementById("table-area-name-prompt").style.opacity = "0";
            }, 5000);
            name_input.blur();
          });
      } else if (key === "Escape") {
        reverseValue(key);
      }
    }
  }
  function changeValue() {
    name_input.setAttribute("value", new_workout_name);
    name_input.value = new_workout_name;
    let form_element = document.getElementById("form-" + current_workout_name.replaceAll(" ", "_"));
    let input_element = form_element.children[0].children[0];
    let input_value = "" +
      input_element.value.substring(0, input_element.value.indexOf("_") + 1) +
      new_workout_name.replaceAll(" ", "_") +
      input_element.value.substring(input_element.value.length - 11);

    form_element.children[0].children[1].children[0].innerHTML = new_workout_name;
    input_element.setAttribute("value", input_value);
    input_element.setAttribute("id", "form-input-" + new_workout_name.replaceAll(" ", "_"));
    form_element.children[0].setAttribute("id", "form-div-" + new_workout_name.replaceAll(" ", "_"));
    form_element.setAttribute("id", "form-" + new_workout_name.replaceAll(" ", "_"));
  }
  function reverseValue(cause) {
    name_input.setAttribute("value", current_workout_name);
    name_input.value = current_workout_name;
    name_input.removeEventListener("focusout", focusLossHandler);
    name_input.removeEventListener("keydown", keyDownHandler);
    if (cause === "Escape") {
      name_input.blur()
    } else {
    }
  }
}

//Changing workout's date
export function onWorkoutDateFocusHandler() {
  let date_input = this;
  let current_workout_name = document.getElementById("table-area-workout-name").getAttribute("value");
  let current_workout_date = date_input.getAttribute("value");
  let new_workout_date = "";
  let date_button = document.getElementById("header-area-date-button");
  document.addEventListener("click", focusLossHandler);
  date_input.addEventListener("keydown", keyDownHandler);
  date_button.addEventListener("click", changeValue);
  showButton();

  function showButton() {
    let button = document.getElementById("header-area-date-button");
    button.style.display = "block";
    setTimeout(
      () => { button.style.opacity = 1 },
      1
    );
  }
  function hideButton() {
    let button = document.getElementById("header-area-date-button");
    let duration = ((Number(getComputedStyle(button).transitionDuration.slice(0, -1))) * 1000);
    button.style.opacity = 0;
    setTimeout(
      () => { button.style.display = "none" },
      duration
    );
  }
  function togglePrompt(isDateInputValid) {
    let prompt_element = document.getElementById("table-area-date-prompt");
    prompt_element.style.opacity = "1";
    if (!isDateInputValid) {
      let prompt_fade_duration = Number(getComputedStyle(prompt_element).transitionDuration.slice(0, -1));
      let default_prompt_HTML = prompt_element.children[0].innerHTML;
      reverseValue();
      console.log(prompt_element.children[0].innerHTML);
      prompt_element.children[0].innerHTML = "Invalid date";
      setTimeout(() => {
        prompt_element.children[0].innerHTML = default_prompt_HTML;
      }, 2000 + prompt_fade_duration);
    }
    setTimeout(() => {
      prompt_element.style.opacity = "0";
    }, 2000);
  }
  function keyDownHandler({ key }) {
    new_workout_date = date_input.value;
    if (key === "Enter") {
      changeValue();
    } else if (key === "Escape") {
      reverseValue(key);
      hideButton();
    }
  }
  function changeValue() {
    new_workout_date = date_input.value;
    let isDateInputValid = true;
    if (new_workout_date.length != 10 || new_workout_date.slice(0, 4) < 1900 || new_workout_date.slice(0, 4) > 2999) {
      isDateInputValid = false;
      hideButton();
      reverseValue();
      togglePrompt(isDateInputValid);
      return;
    }

    $.ajax({
      url: "./scripts_php/RedateWorkout.php",
      method: "POST",
      data: {
        new_workout_date: new_workout_date,
        current_workout_name: current_workout_name
      }
    })
      .done(() => {
        document.getElementById("table-area-date-prompt").innerHTML = "<p>Date changed</p>";
        hideButton();
        togglePrompt(isDateInputValid);
        date_input.removeEventListener("focusout", focusLossHandler);
        date_input.removeEventListener("keydown", keyDownHandler);
        date_input.blur();
      })
      .fail(() => {
        document.getElementById("table-area-date-prompt").innerHTML = "<p>Could not change date</p>";
        reverseValue();
        hideButton();
        togglePrompt(isDateInputValid);
        date_input.blur();
      });
    date_input.setAttribute("value", new_workout_date);
    date_input.value = new_workout_date;
    let form_element = document.getElementById("form-" + current_workout_name.replaceAll(" ", "_"));
    let input_element = form_element.children[0].children[0];
    let input_value = "" +
      input_element.value.substring(0, input_element.value.length - 10) +
      new_workout_date.replaceAll("-", "_");
    let inner_html = new_workout_date.substring(5) + "-" + new_workout_date.substring(0, 4)
    form_element.children[0].children[1].children[1].innerHTML = inner_html;
    input_element.setAttribute("value", input_value);
    document.removeEventListener("click", focusLossHandler);
    date_input.removeEventListener("keydown", keyDownHandler);
    date_button.removeEventListener("click", changeValue);
  }
  function reverseValue(cause) {
    date_input.setAttribute("value", current_workout_date);
    date_input.value = current_workout_date;
    date_input.removeEventListener("keydown", keyDownHandler);
    if (cause === "Escape") {
      date_input.blur()
    } else {
    }
  }
  function focusLossHandler(event) {
    if (!document.getElementById("table-area-date").contains(event.target)
      && !(event.target == document.getElementById("table-area-menu-redate"))) {
      reverseValue();
      hideButton();
      document.removeEventListener("click", focusLossHandler);
      date_input.removeEventListener("keydown", keyDownHandler);
      date_button.removeEventListener("click", changeValue);
    }
  }
}

