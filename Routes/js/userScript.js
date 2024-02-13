const popUpBox2 = document.querySelector(".pop_box2");
const btnHide2 = document.querySelector(".btn_cancel2");
const setting = document.querySelector(".setting");

setting.addEventListener("click", () => {
  popUpBox2.classList.toggle("visible");
});
btnHide2.addEventListener("click", () => {
  popUpBox2.classList.remove("visible");
});
