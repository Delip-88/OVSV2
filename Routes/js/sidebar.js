const sidebar = document.querySelector(".sidebar");

function menu_open() {
  sidebar.style.display = "block";
  sidebar.style.left = "0px";
  x.style.display = "block";
  bar.style.display = "none";
}

const bar = document.querySelector(".fa-bars");
bar.addEventListener("click", menu_open);

const x = document.querySelector(".fa-x");

x.addEventListener("click", close_menu);

function close_menu() {
  sidebar.style.left = "-180px";
  bar.style.display = "block";
}

// Function to check window width and adjust visibility of menu icons
function checkWindowWidth() {
  const windowWidth = window.innerWidth;
  if (windowWidth > 650) {
    // Hide menu icons if window width is greater than 650px
    bar.style.display = "none";
    x.style.display = "none";
    sidebar.style.display = "none";
  } else {
    // Show menu icons if window width is less than or equal to 650px
    bar.style.display = "block";
  }
}

// Initial check when the page loads
checkWindowWidth();

// Add event listener for window resize event
window.addEventListener("resize", checkWindowWidth);
