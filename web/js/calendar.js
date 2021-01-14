const calendar = document.getElementById("calendar");

const weekDayToCalenderColumnId = {
  0: "sun-col",
  1: "mon-col",
  2: "tue-col",
  3: "wed-col",
  4: "thu-col",
  5: "fri-col",
  6: "sat-col",
}

const parseDateStringToArray = (dateString) => {
  // This function accepts a dateString as returned by the database
  // and converts it into a standardized date format readable by all JS
  // engines. The result must be passed with the spread syntax, i.e.
  // const myDate = new Date(...parseDateStringToArray(myDateString));
  // The format supplied by the Postgres database works fine
  // in modern versions of Chrome and Firefox, but breaks in Safari and
  // probably other browsers.
  const regex = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/;
  let [fullDate, year, monthIndex, day, hour, minute] = dateString.match(regex);
  monthIndex -= 1;
  return [year, monthIndex, day, hour, minute];
}

const createEvent = (startDateString, endDateString, status, title, id) => {
  const singleHourHeightInPx = 48;
  const columnTopOffsetInPx = 12;
  const startDate = new Date(...parseDateStringToArray(startDateString));
  const endDate = new Date(...parseDateStringToArray(endDateString));
  
  const startWeekday = startDate.getDay();
  const endWeekDay = startDate.getDay();

  if (startWeekday !== endWeekDay) {
    // TODO: Fix function for days that 
  }
  const startHour = startDate.getHours();
  const startMinutes = startDate.getMinutes();
  const endHour = endDate.getHours();
  const endMinutes = endDate.getMinutes();
  const startPositionInPx = columnTopOffsetInPx + (startHour + startMinutes / 60) * singleHourHeightInPx
  const eventHeightInPx = ((endHour - startHour) + (endMinutes - startMinutes)/60) * singleHourHeightInPx

  const eventLink = document.createElement("a");
  const eventDiv = document.createElement("div");
  // The JSON encoded by PHP encodes booleans to "t" / "f"
  eventDiv.className = status == "approved" ? "event event--confirmed" : "event event--requested";
  eventDiv.style.top = `${startPositionInPx}px`;
  eventDiv.style.height = `${eventHeightInPx}px`; 
  eventDiv.style.overflowWrap = "break-word";
  eventLink.href = `/booking.php?id=${id}`;
  eventLink.appendChild(eventDiv);

  if (title) {
    const eventTitleElement = document.createTextNode(title);
    eventDiv.appendChild(eventTitleElement);
    const br = document.createElement("br")
    eventDiv.appendChild(br);
  }

  const timeInfo = document.createElement("span");
  timeInfo.className = "event__time";
  timeInfo.innerHTML = `${prettyHourAndMinute(startHour, startMinutes)}&ndash;${prettyHourAndMinute(endHour, endMinutes)}`;
  eventDiv.appendChild(timeInfo);
  const calendarColumnId = weekDayToCalenderColumnId[startWeekday];
  const calendarColumn = document.querySelector(`#${calendarColumnId} .hours-column`);
  calendarColumn.appendChild(eventLink);
}

const prettyHourAndMinute = (hour, minute) => {
  return `${hour.toString().padStart(2, "0")}:${minute.toString().padStart(2, "0")}`;
}

const highlightCurrentWeekday = () => {
  const today = new Date().getDay();
  const todayDivID = weekDayToCalenderColumnId[today];
  const todayColumnDiv = document.getElementById(todayDivID);
  todayColumnDiv.classList.add("today");

}
if (JSON.parse(calendar.dataset.currentWeek)) {
  highlightCurrentWeekday();
}

// Get events from the data-attribute in HTML and draw them in the DOM:
window.addEventListener("DOMContentLoaded", () => {
  console.log("DOM loaded, populating with events")
  const events = JSON.parse(calendar.getAttribute('data-events'));
  for (event of events) {
    createEvent(event.starttime, event.endtime, event.status, event.title, event.id);
  }
});
// createEvent("2020-08-14 21:00", "2020-08-14 23:00", "requested", "Barmøte");
// createEvent("2020-08-15 09:00", "2020-08-15 18:00", "confirmed", "Spring Awakening-Workshop");
// createEvent("2020-08-10 17:15", "2020-08-10 21:00", "confirmed", "Mandagsmøte");