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
function passwordValid() {
  let message = document.querySelector(".passwordmsg");
  let password = document.getElementById("password").value;
  if (!password) {
    message.innerHTML = "Please enter your password";
  } else if (password.length < 8 || password.length > 32) {
    message.innerHTML = "Password must be between 8 and 32 characters";
  } else if (!/\d/.test(password)) {
    message.innerHTML = "Password must include at least one number";
  } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
    message.innerHTML = "Password must include at least one special character";
  } else if (!/[A-Z]/.test(password)) {
    message.innerHTML = "Password must include at least one capital letter";
  } else {
    message.innerHTML = "";
  }
}

function cpasswordValid() {
  let message = document.querySelector(".cpasswordmsg");
  const password = document.getElementById("password").value;
  const rePsw = document.getElementById("cpassword").value;
  if (password !== rePsw) {
    message.innerHTML = "Confirm password doesn't match with password";
  } else {
    message.innerHTML = "";
  }
}

function nameValid() {
  let message = document.querySelector(".namemsg");
  const fname = document.getElementById("name").value;
  if (fname.length < 4 || fname.length > 32) {
    message.innerHTML = "Name must be between 4 and 32 characters";
  } else if (/\d/.test(fname)) {
    message.innerHTML = "Name shouldn't include numbers";
  } else if (/[!@#$%^&*(),.?":{}|<>]/.test(fname)) {
    message.innerHTML = "Name shouldn't include special characters";
  } else {
    message.innerHTML = "";
  }
}

function numberValid() {
  let message = document.querySelector(".numbermsg");
  const num = document.getElementById("number").value;
  if (isNaN(num) || num.length !== 10) {
    message.innerHTML = "Number must be 10 digits";
  } else {
    message.innerHTML = "";
  }
}

//Submit prevention
// const btnSubmit = document.getElementById("submit");
// const formsubmit = document.querySelector("#form");
// formsubmit.addEventListener("submit", (e) => {
//   const messages = document.querySelectorAll(".messagejs");
//   messages.forEach((message) => {
//     if (message.textContent.length > 0) {
//       e.preventDefault();
//     }
//   });
// });
