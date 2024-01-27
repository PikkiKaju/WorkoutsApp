
export function panelHandler() {
  // Hiding/showing the panel
  let css_panel_width = getComputedStyle(document.querySelector(":root")).getPropertyValue("--panel-width").trim();

  if (getCookie('panelHidden')) {
    hidePanel();
  }
  function showPanel() {
    document.getElementById('panel-wrap').style.width = css_panel_width;
    document.getElementById('panel').style.left = '0';
    document.getElementById('panel-hide-button').style.left = css_panel_width;
    document.getElementById('panel-hide-arrow-top').style.transform = 'translate(0, -70%) rotate(45deg)';
    document.getElementById('panel-hide-arrow-bot').style.transform = 'translate(0, -10%) rotate(-45deg)';
  }
  function hidePanel(button_edge) {
    document.getElementById('panel-wrap').style.width = '0px';
    document.getElementById('panel').style.left = '-' + css_panel_width;
    document.getElementById('panel-hide-button').style.left = '0px';
    document.getElementById('panel-hide-arrow-top').style.transform = 'translate(0, -70%) rotate(-45deg)';
    document.getElementById('panel-hide-arrow-bot').style.transform = 'translate(0, -10%) rotate(45deg)';
  }
  if (getCookie("panelHidden") === "TRUE") {
    hidePanel();
  }
  if (getCookie("panelHidden") === "FALSE") {
    showPanel();
  }
  document.querySelector('#panel-hide-button').addEventListener("click", () => {
    if (getCookie("panelHidden") === "TRUE") {
      setCookie("panelHidden", "FALSE", 1)
      showPanel();
    } else {
      setCookie("panelHidden", "TRUE", 1)
      hidePanel();
    }
  });

  //Panel year-wrap
  let year_wraps_arrows = [...document.getElementsByClassName("year-wrap-arrows")];
  year_wraps_arrows.forEach(element => {
    element.addEventListener("click", showYearWrap);
  });
  function showYearWrap(event) {
    let year_wrap;
    event.composedPath().forEach(element => {
      if (element != document && element != window && element.classList.contains("workout-list-year-wrap")) {
        year_wrap = element;
      }
    });

    let wrap_list = year_wrap.children[1];
    let left_arrow = year_wrap.children[0].children[0].children[0];
    let right_arrow = year_wrap.children[0].children[0].children[1];
    let trans_duration = getComputedStyle(document.querySelector(".workout-list-year-wrap")).getPropertyValue("--year-wrap-trans-duration").trim().slice(0, -1) * 1000;

    if (getComputedStyle(wrap_list).display == "block") {
      wrap_list.style.opacity = "0";
      setTimeout(() => {
        wrap_list.style.display = "none";
      }, trans_duration);
      left_arrow.style.transform = "translate(200%, 60%) rotate(-45deg)";
      right_arrow.style.transform = "translate(500%, -40%) rotate(45deg)";
    } else {
      wrap_list.style.display = "block";
      setTimeout(() => {
        wrap_list.style.opacity = "1";
      }, 1);
      left_arrow.style.transform = "translate(200%, 60%) rotate(-135deg)";
      right_arrow.style.transform = "translate(500%, -40%) rotate(135deg)";
    }
  }
}  
