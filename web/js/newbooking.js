const form = document.getElementById("form");
const titleInput = document.getElementById("title");
const descriptionInput = document.getElementById("description");
const dateInput = document.getElementById("date");
const startTimeInput = document.getElementById("starttime");
const endTimeInput = document.getElementById("endtime");
const submitButton = document.getElementById("submit");
const errorMessageDiv = document.getElementById("error");

// Event handler whenever the form is changed
const formDidUpdate = () => {
  // See if all required fields are filled in
  if (
    !titleInput.value ||
    !dateInput.value ||
    !startTimeInput.value ||
    !endTimeInput.value
  ) {
    submitButton.disabled = true;
    return;
  }
  submitButton.disabled = false;
  return;
};

const validateTime = (e) => {
  // Makes sure the end time is after the begin time
  // Use a hard-coded date, as for now, we don't care about it
  // Using January 1 1970 because of this:
  // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/parse
  const startTime = Date.parse(`1 Jan 1970 ${startTimeInput.value}`);
  const endTime = Date.parse(`1 Jan 1970 ${endTimeInput.value}`);
  if (startTime >= endTime) {
    e.preventDefault();
    errorMessageDiv.style.display = "block";
    return;
  }
  errorMessageDiv.style.display = "none";
  return;
};

// Datepicker https://github.com/qodesmith/datepicker
const picker = datepicker("#date", {
  startDay: 1, // Start week on monday
  customDays: ["søn", "man", "tir", "ons", "tor", "fre", "lør"],
  customMonths: [
    "Januar",
    "Februar",
    "Mars",
    "April",
    "Mai",
    "Juni",
    "Juli",
    "August",
    "September",
    "Oktober",
    "November",
    "Desember",
  ],
  minDate: new Date(),
  onSelect: formDidUpdate,
});

form.addEventListener("input", formDidUpdate);
form.addEventListener("submit", validateTime);
