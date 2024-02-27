const navbar = document.querySelector(".sidebar");
function menu_open() {
  navbar.style.display = "block";
  x.style.display = "block";
  bar.style.display = "none";
}

const bar = document.querySelector(".fa-bars");
bar.addEventListener("click", menu_open);

const x = document.querySelector(".fa-x");

x.addEventListener("click", close_menu);

function close_menu() {
  navbar.style.display = "none";
  bar.style.display = "block";
}

// Function to check window width and adjust visibility of menu icons
const headerMenu = document.querySelector(".menu");
function checkWindowWidth() {
  const windowWidth = window.innerWidth;
  if (windowWidth > 650) {
    // Hide menu icons if window width is greater than 650px
    bar.style.display = "none";
    x.style.display = "none";
    navbar.style.display = "none";
  } else {
    // Show menu icons if window width is less than or equal to 650px
    bar.style.display = "block";
  }
}

// Initial check when the page loads
checkWindowWidth();

// Add event listener for window resize event
window.addEventListener("resize", checkWindowWidth);
