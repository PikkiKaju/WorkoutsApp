
export function seriesDropdownHandler() {
  // let wrap_list = this.parentElement.parentElement.parentElement.closest["tbody"];
  let wrap_list = this.closest("table").getElementsByTagName("TBODY")[0];
  let left_arrow = this.children[0];
  let right_arrow = this.children[1];


  let warp_list_display = getComputedStyle(wrap_list).getPropertyValue("--table-body-display");
  let left_arrow_transform = getComputedStyle(left_arrow).transform;
  let right_arrow_transform = getComputedStyle(right_arrow).transform;
  let arrow_transition = getComputedStyle(right_arrow).transitionDuration.trim().slice(0, -1) * 1000;
  let left_arrow_rotated = getComputedStyle(left_arrow).transform.replace("-45deg","-135deg");
  let right_arrow_rotated = getComputedStyle(right_arrow).transform.replace("45deg","135deg");
  
  if (getComputedStyle(wrap_list).display == warp_list_display) {
    wrap_list.style.opacity = "0"; 
    setTimeout(() => {
      wrap_list.style.display = "none";
    }, arrow_transition);
    left_arrow.style.transform = left_arrow_transform;
    right_arrow.style.transform = right_arrow_transform;
  } else {
    wrap_list.style.display = warp_list_display;
    setTimeout(() => {
      wrap_list.style.opacity = "1";
    }, 100);
    left_arrow.style.transform = left_arrow_rotated;
    right_arrow.style.transform = right_arrow_rotated;
  }
}
