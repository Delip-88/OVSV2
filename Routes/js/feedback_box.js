document.querySelectorAll(".setting").forEach((settingBtn, index) => {
    const popUpBox = document.querySelectorAll(".pop_box2")[index];
    const btnHide = popUpBox.querySelector(".btn_cancel2");
  
    settingBtn.addEventListener("click", () => {
      popUpBox.classList.toggle("visible");
    });
  
    btnHide.addEventListener("click", () => {
      popUpBox.classList.remove("visible");
    });
  });
  