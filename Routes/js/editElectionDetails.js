// Function to open edit popup and populate fields with election data
function openEditPopup(electionId, title, stDate, endDate) {
  document.getElementById("editElectionId").value = electionId;
  document.getElementById("editTitle").value = title;
  document.getElementById("editStDate").value = stDate;
  document.getElementById("editEndDate").value = endDate;
  document.getElementById("editPopup").style.display = "block";
}

// Function to close edit popup
function closeEditPopup() {
  document.getElementById("editPopup").style.display = "none";
}

// Event listener for edit buttons
const editButtons = document.querySelectorAll(".edit");
editButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const eCard = this.closest(".eCard");
    const title = eCard.dataset.title;
    const stDate = eCard.querySelector(".stDate").textContent.trim(); // Trim whitespace
    const endDate = eCard.querySelector(".endDate").textContent.trim(); // Trim whitespace
    const electionId = eCard.querySelector('[name="eId"]').value;
    openEditPopup(electionId, title, stDate, endDate);
  });
});
