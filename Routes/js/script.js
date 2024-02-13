// Pop up box js
const btnShow = document.querySelector(".btn_more");
const btnHide = document.querySelector(".btn_cancel");
const popUpBox = document.querySelector(".pop_box");

function openModal() {
  popUpBox.classList.toggle("visible");
}
function closeModal() {
  popUpBox.classList.remove("visible");
}

btnShow.addEventListener("click", openModal);
btnHide.addEventListener("click", closeModal);
