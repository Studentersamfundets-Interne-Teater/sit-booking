const helpButton = document.getElementById("help");
const infoText = document.getElementById("info")

helpButton.addEventListener("click", e => {
  if (!infoText.style.maxHeight) {
    // .scrollHeight includes content not visible
    // https://developer.mozilla.org/en-US/docs/Web/API/Element/scrollHeight
    infoText.style.maxHeight = infoText.style.maxHeight = `${infoText.scrollHeight}px`;
    infoText.style.visibility = "visible";
    helpButton.innerText = "Lukk hjelp";
  } else {
    infoText.style.maxHeight = null;
    helpButton.innerText = "Hjelp";
    window.setTimeout(() => infoText.style.visibility = "hidden", 400);
  }
})