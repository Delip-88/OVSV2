<?php
include('connect.php');

$position = $_POST['title'];
$sDate = $_POST['startDate'];
$eDate = $_POST['endDate'];

// Get the current date and time
$currDateTime = date('Y-m-d H:i:s');

// Compare with the start and end dates to determine the election status
if ($currDateTime < $sDate) {
    $status = 'Inactive';
} elseif ($currDateTime >= $sDate && $currDateTime <= $eDate) {
    $status = 'Ongoing';
} else {
    $status = 'Closed';
}

// echo "Status for Insertion: " . $status . "<br>";


$insert = mysqli_query($connect, "INSERT INTO election(Title, StartDate, EndDate, Status) VALUES('$position','$sDate','$eDate', '$status') ");

if ($insert) {
    header("Location: ../Routes/position.php");
    exit;
} else {
    echo "Insertion Failed: " . mysqli_error($connect);
    header("Location: ../Routes/position.php");
    exit; // Ensure script execution stops after the redirect
}



?>