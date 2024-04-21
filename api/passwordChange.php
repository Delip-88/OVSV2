<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['UID'];
    $oldPassword = $_POST['password'];
    $newPassword = $_POST['newPassword'];

    // Retrieve user details
    $queryToCheck = "SELECT * FROM users WHERE id='$userId'";
    $result = $connect->query($queryToCheck);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify old password
        if (password_verify($oldPassword, $row['Password'])) {
            // Hash and update new password
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update query for password change
            $updateQuery = "UPDATE users SET Password='$hash', Full_Name='{$row['Full_Name']}', Address='{$row['Address']}', Number='{$row['Number']}' WHERE Id='$userId'";
            $connect->query($updateQuery);

            // Redirect after successful update
            echo "<script> 
            alert('Password Change sucessully!!');
            window.location.href= '../Routes/user/personalInfo.php';
            </script>";
            exit;
        } else {
            // Old password doesn't match
            echo "<script> 
            alert('Old Password Doesn\'t Match, Try Again !!');
            window.location.href= '../Routes/user/personalInfo.php';
            </script>";
            exit;

        }
    } else {
        // User not found or multiple users found (should not happen)
        echo "
        alert('User not found or multiple users found!');
        window.location.href= '../Routes/user/personalInfo.php';
        </script>";
        exit;
    }
} else {
    // Invalid request method
    echo "<script> 
    alert('Invalid request method');
    window.location.href= '../Routes/user/personalInfo.php';
    </script>";
    exit;

}
?>