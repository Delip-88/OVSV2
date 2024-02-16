const error = document.getElementById("error");
const newPsw = document.getElementById("password");
const rePsw = document.getElementById("cpassword");
const pswform = document.getElementById("form");
const fname = document.getElementById("name");
const num = document.getElementById("number");
const age = document.getElementById("age");

pswform.addEventListener("submit", (e) => {
  let messages = [];
  if (fname.value.trim() === "") {
    messages.push("Name cannot be empty");
  }
  if (age.value < 18) {
    messages.push("You haven't reached the age , yet");
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

document.addEventListener("DOMContentLoaded", function () {
  var dobInput = document.getElementById("dob");
  var ageInput = document.getElementById("age");

  dobInput.addEventListener("input", function () {
    var dobValue = dobInput.value;
    if (dobValue) {
      var dobDate = new Date(dobValue);
      var today = new Date();
      var age = today.getFullYear() - dobDate.getFullYear();
      var monthDiff = today.getMonth() - dobDate.getMonth();
      if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < dobDate.getDate())
      ) {
        age--;
      }
      ageInput.value = age;
    } else {
      ageInput.value = ""; // Reset age field if dob is cleared
    }
  });
});
