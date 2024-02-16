<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['UID'];
    $oldPassword = $_POST['password'];
    $newPassword = $_POST['newPassword'];

    // Retrieve user details
    $queryToCheck = "SELECT * FROM validuser WHERE id='$userId'";
    $result = $connect->query($queryToCheck);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify old password
        if (password_verify($oldPassword, $row['Password'])) {
            // Hash and update new password
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update query for password change
            $updateQuery = "UPDATE validuser SET Password='$hash', Full_Name='{$row['Full_Name']}', Address='{$row['Address']}', Number='{$row['Number']}' WHERE Id='$userId'";
            $connect->query($updateQuery);

            // Redirect after successful update
            header("Location: ../Routes/userPart/personalInfo.php");
            exit;
        } else {
            // Old password doesn't match
            echo "Old Password Doesn't Match, Try Again !!";
        }
    } else {
        // User not found or multiple users found (should not happen)
        echo "User not found or multiple users found!";
    }
} else {
    // Invalid request method
    echo "Invalid request method";
}
?>