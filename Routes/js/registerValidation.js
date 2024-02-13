const error = document.getElementById("error");
const newPsw = document.getElementById("password");
const rePsw = document.getElementById("cpassword");
const pswform = document.getElementById("form");
const fname = document.getElementById("name");
const num = document.getElementById("number");

pswform.addEventListener("submit", (e) => {
  let messages = [];
  if (fname.value.trim() === "") {
    messages.push("Name cannot be empty");
  }
  if (newPsw.value.length < 8) {
    messages.push("Password length should be at least 8 characters");
  }
  if (newPsw.value !== rePsw.value) {
    messages.push("New Password and Confirm password do not match");
  }

  if (num.value.length !== 10) {
    messages.push("Number must be 10 digits");
  }
  let pattern = /^9/;
  if (!pattern.test(num.value)) {
    messages.push("First digit of the number should be 9");
  }
  if (messages.length > 0) {
    e.preventDefault();
    error.innerText = messages.join(", ");
  }
});
