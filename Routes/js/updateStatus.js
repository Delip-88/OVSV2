function updateElectionStatus() {
  const stats = document.querySelectorAll(".status");
  const openedDate = document.querySelectorAll(".stDate");
  const closingDate = document.querySelectorAll(".endDate");
  const currentDateTIme = new Date();

  stats.forEach((e, index) => {
    const openedDateTimeStr = openedDate[index].innerText;
    const closingDateTimeStr = closingDate[index].innerText;

    const openedDateTimeObj = new Date(openedDateTimeStr);
    const closingDateTimeObj = new Date(closingDateTimeStr);

    let newStatus;
    if (currentDateTIme < openedDateTimeObj) {
      newStatus = "Inactive";
      e.textContent = newStatus;
      e.parentNode.parentNode.classList.add("inactive");
    } else if (
      currentDateTIme >= openedDateTimeObj &&
      currentDateTIme <= closingDateTimeObj
    ) {
      newStatus = "Ongoing";
      e.textContent = newStatus;
      e.parentNode.parentNode.classList.add("Ongoing");
    } else {
      newStatus = "Closed";
      e.textContent = newStatus;
      e.parentNode.parentNode.classList.add("closed");
    }

    const electionTitle = e.parentNode.parentNode.dataset.title; // Get election title from data attribute

    // console.log("Debugging: Current Status before update -", newStatus);

    // Make an AJAX request to update the database
    $.ajax({
      type: "POST",
      url: "../../api/updateElectionStatus.php",
      data: {
        electionTitle: electionTitle,
        newStatus: newStatus,
      },
      success: function (response) {
        console.log("Database updated successfully");
      },
      error: function (error) {
        console.error("Error updating database: " + error.responseText);
      },
    });
  });
}

// Call the function when the document is ready or whenever needed
$(document).ready(function () {
  updateElectionStatus();
});
