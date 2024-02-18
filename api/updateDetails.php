<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['uid'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    $query = "UPDATE validuser SET Full_Name='$name', Address='$address', Number='$number' WHERE Id='$userId'";
    $update = mysqli_query($connect, $query);

    if ($update) {
        // Fetch updated user data from the database
        $query = "SELECT * FROM validuser WHERE Id='$userId'";
        $result = mysqli_query($connect, $query);
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