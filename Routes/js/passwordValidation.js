function passwordValid(inputValue) {
  let message = document.querySelector(".passwordmsg");
  let password = inputValue;
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

function cpasswordValid(inputValue) {
  const newPsw = document.getElementById("newPassword").value;
  const rePsw = document.getElementById("rePassword").value;
  let message = document.querySelector(".cpasswordmsg");
  if (newPsw !== rePsw) {
    message.innerHTML = "Confirm password doesn't match with password";
  } else {
    message.innerHTML = "";
  }
}

function nameValid(inputValue) {
  let message = document.querySelector(".namemsg");
  const fname = inputValue;
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

function numberValid(inputValue) {
  let message = document.querySelector(".numbermsg");
  const num = inputValue;
  if (isNaN(num) || num.length !== 10) {
    message.innerHTML = "Number must be 10 digits";
  } else {
    message.innerHTML = "";
  }
}

//prevent submittion if validation not solved

const forms = document.querySelectorAll(".form");
forms.forEach((form) => {
  form.addEventListener("submit", (e) => {
    let messages = form.querySelectorAll(".messagejs");
    messages.forEach((msg) => {
      if (msg.textContent.trim().length > 0) {
        e.preventDefault();
      }
    });
  });
});
