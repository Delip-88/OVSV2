const error = document.getElementById("error");
const newPsw = document.getElementById("password");
const rePsw = document.getElementById("cpassword");
const pswform = document.getElementById("form");

pswform.addEventListener("submit", (e) => {
  let messages = [];

  if (newPsw.value.length < 8) {
    messages.push("password lenght should be at least 8 digit");
  }
  if (newPsw.value !== rePsw.value) {
    messages.push("NewPassword and Confirm password doesnt match");
  }
  if (messages.length > 0) {
    e.preventDefault();
    error.innerText = messages.join(", ");
  }
});

const infoform = document.getElementById("adC");
const num = document.getElementById("number");
infoform.addEventListener("submit", (e) => {
  let messages = [];
  if (num.value.length !== 10) {
    messages.push("Number must be 10 digits");
  }
  let pattern = /^9/;
  if (pattern.test(num.value) == false) {
    messages.push("First letter of num should be 9");
  }
  if (messages.length > 0) {
    e.preventDefault();
    error.style.display = "block";
    error.innerText = messages.join(", ");
  }
});
