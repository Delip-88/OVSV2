function showConfirmation(formId) {
    // Show the confirmation message div
    var confirmationDiv = document.getElementById('confirmationMessage');
    confirmationDiv.style.display = 'block';

    // Change the onclick event of the submit button
    var submitBtn = document.querySelector('.voteBtn');
    submitBtn.onclick = function() {
        if (confirm("Are you sure you want to submit?")) {
            document.getElementById(formId).submit();
            confirmationDiv.style.display = 'none'; // Hide the confirmation message
            return true; // Allow form submission
        } else {
            return false; // Prevent form submission
        }
    };
}
