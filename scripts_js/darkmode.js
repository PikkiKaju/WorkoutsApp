
//changing dark/light mode
function darkmodeClassesAdd(edit_image_name_src) {
  let element;
  document.body.classList.add("darkmode");
  document.getElementById("add-new-workout-window").classList.add("darkmode");
  document.getElementById("add-workout-name").classList.add("darkmode");
  document.getElementById("add-workout-date").classList.add("darkmode");
  document.getElementById("panel").classList.add("darkmode");
  document.getElementById("panel-hide-button").classList.add("darkmode");
  if (element = document.getElementById("workout-added")) {
    element.classList.add("darkmode");
  }
  if (element = document.getElementById("table-area-workout-name")) {
    element.classList.add("darkmode");
    document.getElementById("table-area-name-image").src = edit_image_name_src;
    document.getElementById("table-area-workout-date").classList.add("darkmode");
  }
  let yearWrapElements = [...document.getElementsByClassName("workout-list-year-wrap")];
  yearWrapElements.forEach(elem => {
    elem.classList.add("darkmode");
  });
  let stripeElements = [...document.getElementsByClassName("table-area-stripe")];
  stripeElements.forEach(elem => {
    elem.classList.add("darkmode");
  });
  let crossElements = [...document.getElementsByClassName("button-cross")];
  crossElements.forEach(elem => {
    elem.classList.add("button-cross-darkmode");
  });
  let formElements = [...document.getElementsByClassName("workout-div")];
  formElements.forEach(elem => {
    elem.classList.add("darkmode");
    if (elem.classList.contains("workout-div-displayed")) {
      elem.classList.add("darkmode-workout-form");
    }
  });
  if (document.getElementById("add-exercise-form-div")) {
    document.getElementById("add-exercise-form-div").classList.add("darkmode");
  }
  if (document.getElementById("add-exercise-form-name")) {
    let exerciseFormElements = [...document.getElementsByClassName("add-exercise-form-input")];
    exerciseFormElements.forEach(elem => {
      elem.classList.add("darkmode");
    });
    document.getElementById("add-exercise-form-name").classList.add("darkmode");
    document.getElementById('add-series-button').classList.add('darkmode');
  }

}
function darkmodeClassesRemove(edit_image_name_src) {
  let element;
  document.body.classList.remove("darkmode");
  document.getElementById("add-new-workout-window").classList.remove("darkmode");
  document.getElementById("add-workout-name").classList.remove("darkmode");
  document.getElementById("add-workout-date").classList.remove("darkmode");
  document.getElementById("panel").classList.remove("darkmode");
  document.getElementById("panel-hide-button").classList.remove("darkmode");
  if (element = document.getElementById("workout-added")) {
    element.classList.remove("darkmode");
  }
  if (element = document.getElementById("table-area-workout-name")) {
    element.classList.remove("darkmode");
    document.getElementById("table-area-name-image").src = edit_image_name_src;
    document.getElementById("table-area-workout-date").classList.remove("darkmode");
  }
  let yearWrapElements = [...document.getElementsByClassName("workout-list-year-wrap")];
  yearWrapElements.forEach(elem => {
    elem.classList.remove("darkmode");
  });
  let stripeElements = [...document.getElementsByClassName("table-area-stripe")];
  stripeElements.forEach(elem => {
    elem.classList.remove("darkmode");
  });
  let crossElements = [...document.getElementsByClassName("button-cross")];
  crossElements.forEach(elem => {
    elem.classList.remove("button-cross-darkmode");
  });
  let formElements = [...document.getElementsByClassName("workout-div")];
  formElements.forEach(elem => {
    elem.classList.remove("darkmode");
    if(elem.classList.contains("workout-div-displayed")) {
      elem.classList.remove("darkmode-workout-form");
    }
  });
  if (document.getElementById("add-exercise-form-div")) {
    document.getElementById("add-exercise-form-div").classList.remove("darkmode");
  }
  if (document.getElementById("add-exercise-form-name")) {
    let exerciseFormElements = [...document.getElementsByClassName("add-exercise-form-input")];
    exerciseFormElements.forEach(elem => {
      elem.classList.remove("darkmode");
    });
    document.getElementById("add-exercise-form-name").classList.remove("darkmode");
    document.getElementById('add-series-button').classList.remove('darkmode');
  }
}

export function darkmodeToggle(edit_image_name_src) {
  let darkmodeCookie = getCookie("darkmodeToggled");

  if(darkmodeCookie != null && darkmodeCookie != "") {
    if (darkmodeCookie == "TRUE") {
      edit_image_name_src = "source/images/edit_name_dark.png";
      document.querySelector("#darkmode-switch").classList.add("darkmode-switched");
      document.querySelector("#darkmode-switch-node").classList.add("darkmode-switched-node");
      darkmodeClassesAdd(edit_image_name_src);
    } else {
      edit_image_name_src = "source/images/edit_name_light.png";
      document.querySelector("#darkmode-switch").classList.remove("darkmode-switched");
      document.querySelector("#darkmode-switch-node").classList.remove("darkmode-switched-node");
      darkmodeClassesRemove(edit_image_name_src);
    }
  }
}
