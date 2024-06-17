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

  // Check length requirements
  if (fname.length < 4 || fname.length > 32) {
    message.innerHTML = "Name must be between 4 and 32 characters";
    return;
  }

  // Check for numbers
  if (/\d/.test(fname)) {
    message.innerHTML = "Name shouldn't include numbers";
    return;
  }

  // Check for special characters
  if (/[!@#$%^&*(),.?":{}|<>]/.test(fname)) {
    message.innerHTML = "Name shouldn't include special characters";
    return;
  }

  // If all validations pass
  message.innerHTML = "";
}

function dateValid() {
  let message = document.querySelector(".datemsg");
  const dateInput = document.getElementById("dob").value;

  // Get the current date
  const currentDate = new Date();
  
  // Parse the input date
  const inputDate = new Date(dateInput);

  // Check if the date is valid
  if (isNaN(inputDate.getTime())) {
    message.innerHTML = "Please enter a valid date";
    return;
  }

  // Calculate the age
  const age = currentDate.getFullYear() - inputDate.getFullYear();
  const monthDifference = currentDate.getMonth() - inputDate.getMonth();
  const dayDifference = currentDate.getDate() - inputDate.getDate();

  // Adjust age if the birth date has not occurred yet this year
  if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
    age--;
  }

  // Validate the age range
  if (age < 12 || age > 90) {
    message.innerHTML = "Age must be between 12 and 90 years";
  } else {
    message.innerHTML = "";
  }
}


function emailValid() {
  let message = document.querySelector(".emailmsg");
  const email = document.getElementById("email").value;

  // Basic email pattern that does not start with a number
  const emailPattern = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  // Validate the email address
  if (!emailPattern.test(email)) {
    message.innerHTML = "Please enter a valid email address";
  } else {
    message.innerHTML = "";
  }
}

function fileValid() {
  let message = document.querySelector(".filemsg");
  const fileInput = document.getElementById("image");
  const file = fileInput.files[0]; // Get the file object
  const filePath = fileInput.value;

  // Allowed file extensions
  const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

  // Validate the file extension
  if (!allowedExtensions.exec(filePath)) {
    message.innerHTML = "Only .jpg, .jpeg, and .png files are allowed";
    fileInput.value = ""; // Clear the input value
    return false;
  }

  // Validate the file size (1 MB = 1,048,576 bytes)
  if (file && file.size > 1048576) {
    message.innerHTML = "File size must be less than 1 MB";
    fileInput.value = ""; // Clear the input value
    return false;
  }

  // If all validations pass
  message.innerHTML = "";
  return true;
}

// Add an event listener to trigger validation on file change
document.getElementById("image").addEventListener("change", fileValid);



function numberValid() {
  let message = document.querySelector(".numbermsg");
  const num = document.getElementById("number").value;

  // Check if the input is a number and has a length of 10
  if (isNaN(num) || num.length !== 10) {
    message.innerHTML = "Number must be 10 digits";
    return;
  }

  // Check if the number starts with '9'
  if (num[0] !== '9') {
    message.innerHTML = "Number must start with '9'";
    return;
  }

  // Check if all digits are the same
  if (/^(\d)\1+$/.test(num)) {
    message.innerHTML = "All numbers cannot be the same digit";
    return;
  }

  // If all validations pass
  message.innerHTML = "";
}


//Submit prevention
const btnSubmit = document.getElementById("submit");
const formsubmit = document.querySelector("#form");
formsubmit.addEventListener("submit", (e) => {
  const messages = document.querySelectorAll(".messagejs");
  messages.forEach((message) => {
    if (message.textContent.length > 0) {
      e.preventDefault();
    }
  });
});
