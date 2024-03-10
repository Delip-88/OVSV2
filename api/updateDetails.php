<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['uid'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Use prepared statement to prevent SQL injection
    $query = "UPDATE users SET Full_Name=?, Address=?, Number=? WHERE Id=?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $address, $number, $userId);
    $update = mysqli_stmt_execute($stmt);

    if ($update) {
        // Fetch updated user data from the database
        $query = "SELECT * FROM users WHERE Id=?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $updatedUserData = mysqli_fetch_assoc($result);

        // Send the updated user data as a JSON response
        header('Content-Type: application/json');
        echo json_encode($updatedUserData);
        exit;
    } else {
        // Handle update failure
        echo json_encode(array('error' => 'Failed to update user details'));
        exit;
    }
} else {
    // Handle invalid request method
    echo json_encode(array('error' => 'Invalid request method'));
    exit;
}

// Inside updateDetails.php
if ($_FILES['changeImage']['error'] === UPLOAD_ERR_OK) {
    $imageTmpName = $_FILES['changeImage']['tmp_name'];
    $imagePath = '../uploads/' . $_FILES['changeImage']['name'];
    move_uploaded_file($imageTmpName, $imagePath);
    // Update database with $imagePath or do further processing as needed
}


?>