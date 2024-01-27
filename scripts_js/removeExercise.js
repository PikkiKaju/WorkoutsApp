// Removing exercise from data base and Table
export function removeExercise() {
  let element = this;
  let exercise_id = element.getAttribute('id').slice(23).toString();
  $(function() {
    $.ajax( {
      url: "./scripts_php/RemoveExercise.php",
      method: "POST",
      data: {"remove_exercise_id": exercise_id}
    })
    .done( function() {
        $(element).closest('tr').remove();
    })
    .fail( function() {
      console.log("Data could not be send.");
    });

    let i = parseInt(exercise_id) + 1;
    if(document.getElementById("table-row-"+i.toString())) {
      while(document.getElementById("table-row-"+i.toString())) {
        document.getElementById("table-row-" + i.toString()).getElementsByClassName("table-element-id")[0].innerHTML = (i - 1).toString();
        document.getElementById("remove-exercise-button-" + i.toString()).id = "remove-exercise-button-" + (i - 1).toString();
        document.getElementById("table-row-" + i.toString()).id = "table-row-" + (i - 1).toString();
        element.closest("table").closest("tr").remove();
        i++;
      }
    }
    //Hiding Table after removing last exercises
    if((document.getElementsByClassName('table-row')).length == 1) {
      $("#workout-table").remove();
      document.getElementById("no-exercises-label").innerHTML = "No exercises added yet.";
    }
  });
}
