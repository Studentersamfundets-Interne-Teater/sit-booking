const deleteButtons = document.querySelectorAll("#delete-booking");
console.log(deleteButtons);

for (let i = 0; i <= deleteButtons.length; i++) {
  deleteButtons[i].addEventListener("click", e => {
    const deleteButton = e.target;
    if (deleteButton.dataset.clicked === 'false') {
      e.preventDefault();
      deleteButton.dataset.clicked = 'true';
      deleteButton.style.width = "auto";
      deleteButton.innerText = "Trykk igjen for å bekrefte";
      return;
    }
  })
}