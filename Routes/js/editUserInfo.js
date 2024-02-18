// Select the Edit Profile button
const btn = document.querySelector(".btn_more");

// Function to open the popup box and populate it with user data
function openSetData(name, address, number) {
  // Set input field values with user data
  document.getElementById("editName").value = name;
  document.getElementById("editAddress").value = address;
  document.getElementById("editNumber").value = number;
  // Display the popup box
  document.querySelector(".pop_box").style.display = "block";
}

// Function to close the popup box
function closeEditPopup() {
  // Hide the popup box
  document.querySelector(".pop_box").style.display = "none";
}

// Event listener for the Edit Profile button click
btn.addEventListener("click", () => {
  // Get user data from the DOM
  const name = document.querySelector("#Full_Name").textContent.trim();
  const address = document.querySelector("#Address").textContent.trim();
  const number = document.querySelector("#Number").textContent.trim();
  // Open the popup box with user data
  openSetData(name, address, number);
});

// Event listener for form submission
document.getElementById("adC").addEventListener("submit", function (event) {
  event.preventDefault(); // Prevent the form from submitting normally

  // Get form data
  const formData = new FormData(this);
  // Create XMLHttpRequest object
  const xhr = new XMLHttpRequest();

  // Define callback function for when the request state changes
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      // If request is complete
      if (xhr.status === 200) {
        // If request was successful
        // Parse the response JSON containing updated user data
        const updatedUserData = JSON.parse(xhr.responseText);
        // Update DOM with the updated user data
        document.querySelector("#Full_Name").textContent =
          updatedUserData.Full_Name;
        document.querySelector("#Address").textContent =
          updatedUserData.Address;
        document.querySelector("#Number").textContent = updatedUserData.Number;
        // Close the popup after successful update
        closeEditPopup();
      } else {
        // Handle error
        console.error("Error:", xhr.responseText);
      }
    }
  };

  // Open a POST request to the updateDetails.php script
  xhr.open("POST", "../../api/updateDetails.php", true);
  // Send form data as the request payload
  xhr.send(formData);
});
