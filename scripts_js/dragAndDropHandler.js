
//Drag and Drop

export function draggablesHandler(draggables, neighbourItems) {
  draggables.forEach(draggable => {
    let pivot = draggable.getElementsByTagName("th")[0];
    pivot.addEventListener("dragstart", event => {
      pivot.classList.add("dragging-pivot");
      draggable.classList.add("dragging");
      draggable.getElementsByTagName("TR")[0].classList.add("dragover-overline");
      event.dataTransfer.setDragImage(new Image(), 0, 0);
    });
    pivot.addEventListener("dragend", () => {
      pivot.classList.remove("dragging-pivot");
      draggable.classList.remove("dragging");
      draggable.getElementsByTagName("TBODY")[0].classList.remove("dragover-overline");
      if (neighbourItems.lastMovedItemId != null && neighbourItems.lastAfterItemId != null) {
        $.ajax({
          url: "./scripts_php/MoveExercise.php",
          method: "post",
          data: {
            "moved_exercise_id": neighbourItems.lastMovedItemId,
            "after_exercise_id": neighbourItems.lastAfterItemId
          }
        });
        let draggablesAfter = document.querySelectorAll(".table-row");
        for (let i = 0; i < draggablesAfter.length; i++) {
          draggablesAfter[i].id = "table-row-" + String(i + 1);
        }
      }
    });
  });
}
  
export function containerDragoverHandler(container, neighbourItems) {
  container.addEventListener("dragover", event => {
    event.preventDefault();
    container.addEventListener("dragenter", event => {
      event.preventDefault();
    });

    const afterElement = getDragAfterElement(container, event.clientY);
    const draggable = document.querySelector(".dragging");

    neighbourItems.lastMovedItemId = draggable.id.slice(10);

    if (afterElement == null) {
      container.appendChild(draggable);
      neighbourItems.lastAfterItemId = (container.children.length + 1);
    } else {
      container.insertBefore(draggable, afterElement);
      neighbourItems.lastAfterItemId = afterElement.id.slice(10);
    }
    let i = 1;
    [...container.children].forEach(elem => {
      elem.getElementsByClassName("table-element-id")[0].innerHTML = "<div>"+i+"</div>";
      i++;
    });
  });
}

//Finding element after the draged element
function getDragAfterElement(container, y) {
  const draggableElements = [...container.querySelectorAll(".table-row:not(.dragging)")];
  return draggableElements.reduce(function (closest, child) {
    const box = child.getBoundingClientRect();
    const offset = y - box.top - box.height / 2;
    if (offset < 0 && offset > closest.offset) {
      return { offset: offset, element: child };
    } else {
      return closest;
    }
  }, { offset: Number.NEGATIVE_INFINITY }).element;
}
