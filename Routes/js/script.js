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

// Pop up box js 2
const btnShow2 = document.querySelector(".setting");
const btnHide2 = document.querySelector(".btn_cancel2");
const popUpBox2 = document.querySelector(".pop_box2");

function openModal2() {
  popUpBox2.classList.toggle("visible");
}
function closeModal2() {
  popUpBox2.classList.remove("visible");
}

btnShow2.addEventListener("click", openModal2);
btnHide2.addEventListener("click", closeModal2);

