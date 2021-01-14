const deleteButton = document.getElementById("delete-btn");
const deleteConfirmation = document.getElementById("delete-confirmation");
const cancelButton = document.getElementById("cancel-btn");

console.log(deleteConfirmation);

deleteButton.addEventListener("click", () => {
  deleteConfirmation.style.display = "block";
})

cancelButton.addEventListener("click", () => {
  deleteConfirmation.style.display = "none";
})