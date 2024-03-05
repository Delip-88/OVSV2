const error = document.getElementById("error");
const infoform = document.getElementById("adC");
const num = document.getElementById("number");
const curPsw = document.getElementById("password");
const pError = document.getElementById("passwordError");
const newPsw = document.getElementById("newPassword");
const rePsw = document.getElementById("rePassword");
const pswform = document.getElementById("form");

// password  Change validation
pswform.addEventListener("submit", (e) => {
  let messages = [];

  // Password length validation
  if (
    newPsw.value.length < 8 ||
    rePsw.value.length < 8 ||
    curPsw.value.length < 8
  ) {
    messages.push("Password length should be at least 8 characters");
  }

  // Password match validation
  if (newPsw.value !== rePsw.value) {
    messages.push("New Password and Confirm password do not match");
  }

  // Display password error messages
  if (messages.length > 0) {
    e.preventDefault();
    pError.style.display = "block";
    pError.innerText = messages.join(", ");
  } else {
    pError.style.display = "none"; // Reset error display
  }
});

// Info Form validation
infoform.addEventListener("submit", (e) => {
  let messages = [];

  // Number length validation
  if (num.value.length !== 10) {
    messages.push("Number must be 10 digits");
  }

  // Display number error messages
  if (messages.length > 0) {
    e.preventDefault();
    error.style.display = "block";
    error.innerText = messages.join(", ");
  } else {
    error.style.display = "none"; // Reset error display
  }
});
