// Removing exercise from data base and Table
export function removeSeries() {
  let trElement = this;
  while (trElement.tagName != "TR") {
    trElement = trElement.parentElement
  }
  let table = trElement.parentElement.parentElement;
  let exercise_id, series_id, series_repetitions, series_weights;
  exercise_id = trElement.parentElement.parentElement.getElementsByClassName('table-element-id')[0].children[0].innerHTML;
  series_id = trElement.getAttribute('series-number');
  [...trElement.children].forEach(tag => {
    if (tag.getAttribute('column') == "repetitions") {
      series_repetitions = tag.innerHTML;
    } else if (tag.getAttribute('column') == "weights") {
      series_weights = tag.innerHTML;
    }
  });
  
  $(function () {
    $.ajax( {
      url: "./scripts_php/RemoveSeries.php",
      method: "POST",
      data: {
        "remove_exercise_id": exercise_id,
        "remove_series_id": series_id,
        "remove_series_repetitions": series_repetitions,
        "remove_series_weights": series_weights
      }
    })
    .done( function() {
        $(trElement).remove();
    })
    .fail( function() {
      console.log("Data could not be send.");
    });

    let tbody = table.getElementsByTagName("TBODY")[0];
    if(tbody.children.length > 0) {
      [...tbody.children].forEach((trow, i) => {
        trow.setAttribute("series-number", i + 1);
      });   
    }

    //Hiding Table after removing last exercises
    if((document.getElementsByClassName('table-row')).length == 1) {
      $("#workout-table").remove();
      document.getElementById("no-exercises-label").innerHTML = "No exercises added yet.";
    }
  });
}
