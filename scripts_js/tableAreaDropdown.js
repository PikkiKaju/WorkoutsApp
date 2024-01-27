
export function tableAreaDropdownToggle(focusHandlerListenerAdded) {
  $("#table-area-dropdown-menu").toggle();
  if (!focusHandlerListenerAdded.value) {
    document.addEventListener("click", focusLossHandler);
    focusHandlerListenerAdded.value = true;
  }
  function focusLossHandler(event) {
    if (!document.getElementById("table-area-dropdown").contains(event.target)) {
      $("#table-area-dropdown-menu").hide();
      document.removeEventListener("click", focusLossHandler);
      focusHandlerListenerAdded = false;
    }
  }
  
  document.getElementById("table-area-menu-delete").onclick = () => {
    promptFadeIn();
    document.getElementById("table-prompt-button-yes").onclick = () => {
      deleteWorkout()
    };
    document.getElementById("table-prompt-button-no").onclick = () => {
      promptFadeOut();
    };
  };
}

function promptFadeIn() {
  $("#table-prompt-div").fadeIn(200);
  $("#table-area-dropdown-menu").hide();
  document.getElementsByClassName("table-prompt-box")[0].style.transform = "scaleX(1)";
}

function promptFadeOut() {
  $("#table-prompt-div").fadeOut(200);
  setTimeout(() => {
    document.getElementsByClassName("table-prompt-box")[0].style.transform = "scaleX(0)";
  }, 100);
}

function deleteWorkout() {
  $.ajax({
    url: "./scripts_php/DeleteWorkout.php",
    method: "POST"
  })
  .done(function () {
    promptFadeOut();
    window.location.reload();
  })
  .fail(function () {
    promptFadeOut();
    console.log("Data could not be send.");
  });
}
