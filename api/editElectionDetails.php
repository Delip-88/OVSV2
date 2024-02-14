<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eId = $_POST['electionId'];
    $title = $_POST['title'];
    $stDate = $_POST['stDate'];
    $endDate = $_POST['endDate'];

    $updateQuery = "UPDATE election SET Title = '$title' , StartDate = '$stDate', EndDate = '$endDate', Status = '{$row['Status']}' WHERE Id = '$eId'";

    $insert = mysqli_query($connect, $updateQuery);

    if ($insert) {
        echo "Updation Success";
        header("Location: ../Routes/position.php");
        exit;
    } else {
        echo "Updation Failed " . mysqli_error($connect);
        header("Location: ../Routes/position.php");
        exit;
    }
}
?>