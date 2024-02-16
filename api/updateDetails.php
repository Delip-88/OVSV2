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
        // Redirect the user back to the same page after the update
        header("Location: ../Routes/userPart/personalInfo.php");
        exit;
    } else {
        // Handle update failure
        echo 'Failed to update user details';
        exit;
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method';
    exit;
}
?>