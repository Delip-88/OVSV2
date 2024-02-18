const navbar = document.querySelector(".menu");
function menu_open() {
  navbar.style.width = "250px";
  bar.style.display = "none";
  x.style.display = "block";
  x.style.transform = "translateX(0px)";
}

const bar = document.querySelector(".fa-bars");
bar.addEventListener("click", menu_open);

const x = document.querySelector(".fa-x");

x.addEventListener("click", close_menu);

function close_menu() {
  navbar.style.width = "0px";
  x.style.display = "none";
  bar.style.display = "block";
}
