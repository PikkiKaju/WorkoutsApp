

//Show/Hide add-exercise-form
if (document.getElementById('add-exercise-div')) {
  let seriesEventListenerAdded = false;
  let listEventListenerAdded = false;
  let num_of_series = 1;

  function toggleExercisesList() {
    let json_data = Array();
    $.ajax({
      url: "./scripts_php/GetExercisesList.php",
      method: "POST"
    })
    .done(function (result) {
      json_data = JSON.parse(result);
      json_data.shift();
      let elements_html = "";
      let darkmodeCross = "";
      if (getCookie("darkmodeToggled") == "TRUE") {
        darkmodeCross = "button-cross-darkmode";
      }
      json_data.forEach((element) => {
        elements_html += /*html*/ `
          <div name="${element.ExerciseName}">
            <p>${element.ExerciseName}</p> 
            <button type="button" id="remove-exercise-list-button-${num_of_series}" class="remove-exercise-list-button remove-button-cross">
              <div class="${darkmodeCross}"></div>
              <div class="${darkmodeCross}"></div>
            </button>  
          </div> 
        `  
      });
      document.getElementById("add-exercise-list").innerHTML = elements_html;

      let exercise_list_elements = [...document.getElementById("add-exercise-list").children];
      exercise_list_elements.forEach((element) => {
        element.addEventListener("click", (event) => {
          if (event.target != element.children[1] && !element.children[1].contains(event.target)) {
            document.getElementById("add-exercise-form-name").value = element.getAttribute("name");
            list_wrapper.style.display = "none";
          } else {
            $.ajax({
              url: "./scripts_php/RemoveFromExercisesList.php",
              method: "POST",
              data: { "exercise_name": element.getAttribute("name") }
            })
            .done(function (result) {
              console.log("works");
              element.remove();
            })
            .fail(function () {
              json_data = 0;
              console.log("Exercise could not be removed from list");
            });
          }
        });
      });

      let list_wrapper = document.getElementById("add-exercise-list");
      if (list_wrapper.children.length > 0) list_wrapper.style.display = "block";

      function listFocusLossHandler(event) {
        if (event.target != document.getElementById("add-exercise-list") && event.target != document.getElementById("add-exercise-form-name") && !document.getElementById("add-exercise-list").contains(event.target)) {
          list_wrapper.style.display = "none";
          document.removeEventListener("click", listFocusLossHandler);
        }
      }
      document.addEventListener("click", listFocusLossHandler);
      })
    .fail(function () {
      json_data = 0;
      console.log("Exercises list could not be received");
    });
  }
  function toggleExerciseForm() {
    let form = document.getElementById("add-exercise-form");
    let toggleDuration = Number(getComputedStyle(form).transitionDuration.slice(0, -1)) * 1000;
    let form_displayed = getComputedStyle(form).display != "none";
    if (form_displayed) {
      form.style.top = "-200px";
      form.style.opacity = "0";
      setTimeout(() => {
        form.style.display = "none";
      }, toggleDuration);
      document.getElementById('add-exercise-arrow-left').style.transform = "translate(200%, 60%) rotate(-45deg)";
      document.getElementById('add-exercise-arrow-right').style.transform = "translate(500%, -40%) rotate(45deg)";
    } else {
      form.style.display = "flex";
      setTimeout(() => {
        form.style.top = "0px";
        form.style.opacity = "1";
      }, 1);
      document.getElementById('add-exercise-arrow-left').style.transform = "translate(200%, 60%) rotate(-135deg)";
      document.getElementById('add-exercise-arrow-right').style.transform = "translate(500%, -40%) rotate(135deg)";
      document.getElementById("add-exercise-form-name").focus();
      setTimeout(() => {
      }, toggleDuration);
    }
  }
  function changeSeriesProps() {
    document.querySelectorAll(".single-series-div").forEach((child, i) => {
      if (i != 0) {
        child.querySelector(".series-id-label").innerHTML = String(i + 1) + ".";
        child.querySelectorAll(".add-exercise-form-input")[0].id = "add-exercise-form-repetitions-" + String(i + 1);
        child.querySelectorAll(".add-exercise-form-input")[0].name = "repetitions_" + String(i + 1);
        child.querySelectorAll(".add-exercise-form-input")[1].id = "add-exercise-form-weights-" + String(i + 1);
        child.querySelectorAll(".add-exercise-form-input")[1].name = "weights_" + String(i + 1);
        child.querySelector(".remove-series-button").id = "remove-series-button-" + String(i + 1);
        child.id = "exercise-series-" + String(i + 1);
      }
    });
  }
  function addSeriesOnClickHandler() {
    let repetitions_array = [];
    let weights_array = [];
    for (let i = 0; i < num_of_series; i++) {
      repetitions_array.push(document.getElementById('add-exercise-form-repetitions-' + (i + 1)).value);
      weights_array.push(document.getElementById('add-exercise-form-weights-' + (i + 1)).value);
    }
    let darkmodeCross = "";
    let darkmodeClass = "";
    if (getCookie("darkmodeToggled") == "TRUE") {
      darkmodeClass = "darkmode";
      darkmodeCross = "button-cross-darkmode";
    }
    num_of_series += 1;
    let series_html = /*html*/ `
      <div id="exercise-series-${num_of_series} " class="single-series-div">
        <p class="series-id-label">${num_of_series}</p>
        <input id="add-exercise-form-repetitions-${num_of_series}" class="add-exercise-form-input ${darkmodeClass} " pattern="[0-9]{1,}" title="Must be a number" value="${repetitions_array[num_of_series - 2]}" type="text" name="repetitions_${num_of_series}"  placeholder="Number of repetitions" autocomplete="off" required />
        <br />
        <input id="add-exercise-form-weights-${num_of_series}" class="add-exercise-form-input ${darkmodeClass} " pattern="[0-9]{1,}" title="Must be a number" type="text" name="weights_${num_of_series}" value="${weights_array[num_of_series - 2]}" placeholder="None" />
        <p class="series-weights-label">kg</p>
        <button type="button" id="remove-series-button-${num_of_series}" class="remove-series-button remove-button-cross">
          <div class=" ${darkmodeCross} " ></div>
          <div class=" ${darkmodeCross} " ></div>
        </button>
      </div>
    `;

    document.getElementById('series-div').innerHTML += series_html;
    for (let i = 0; i < num_of_series - 1; i++) {
      document.getElementById('add-exercise-form-repetitions-' + (i + 1)).value = repetitions_array[i];
      document.getElementById('add-exercise-form-weights-' + (i + 1)).value = weights_array[i];
    }

    // Removing series inputs from add-exercise-form
    if (document.getElementsByClassName("remove-series-button")) {
      document.querySelectorAll(".remove-series-button").forEach(element => {
        element.addEventListener("click", () => {
          element.parentElement.remove();
          num_of_series--;
          changeSeriesProps();
        });
      });
    }
    addValidationToInputs();
  }

  // checking if user inputs are only numbers
  function addValidationToInputs() {
    let inputElements = [...document.getElementsByClassName("add-exercise-form-input")]; 
    inputElements.shift();
    inputElements.forEach(element => {
      element.onkeypress = (event) => {
        let charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
        } else {
          return true;
        }
      };
    });
  }

  document.getElementById("add-exercise-button").addEventListener("click", () => {
    if (!listEventListenerAdded) {
      document.querySelector("#add-exercise-form-name").addEventListener("click", () => {
        toggleExercisesList();
      });
      listEventListenerAdded = true;
    }
    toggleExerciseForm();

    // Adding series inputs to add-exercise-form
    if (!seriesEventListenerAdded) {
      document.querySelector("#add-series-button").addEventListener("click", addSeriesOnClickHandler);
      seriesEventListenerAdded = true;
    }
  });

}