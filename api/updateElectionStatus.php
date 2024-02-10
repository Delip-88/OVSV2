<?php
echo "Script is executed.";

include("connect.php");

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the AJAX request
    $electionTitle = $_POST['electionTitle'];
    $newStatus = $_POST['newStatus'];

    // echo "Received Election Title: " . $electionTitle . "<br>";
    // echo "Received New Status: " . $newStatus . "<br>";

    // Update the database record
    $queryUpdateStatus = "UPDATE election SET Status = ? WHERE Title = ?";
    $stmtUpdateStatus = mysqli_prepare($connect, $queryUpdateStatus);
    mysqli_stmt_bind_param($stmtUpdateStatus, 'ss', $newStatus, $electionTitle);

    if (mysqli_stmt_execute($stmtUpdateStatus)) {
        // Respond with a success message
        echo "Database updated successfully";
    } else {
        // Respond with an error message
        echo "Error updating database: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmtUpdateStatus);
    mysqli_close($connect); // Close the database connection
} else {
    // Respond with an invalid request message
    echo "Invalid request";
}
?>
