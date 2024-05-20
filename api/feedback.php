<?php
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    $uId = $_POST['uid'];
    $userName = $_POST['user_name'];
    $eId = $_POST['eid'];
    $eTitle = $_POST['etitle'];
    $feedback = $_POST['feedback'];


    $insert = "INSERT INTO feedback (User_Id, User_Name, Election_Id, Election_Title, Feedback) VALUES ('$uId', '$userName', '$eId', '$eTitle', '$feedback')";
    
    if (mysqli_query($connect, $insert)) {
        echo "<script>alert('Feedback Submitted Successfully');
        window.location.href='../Routes/user/results.php';
        </script>";
        exit;
    } else {
        echo "<Script> alert('Failed to Submit Feedback'); 
        window.location.href='../Routes/user/results.php';
        </script>";  
        exit;
    }
}
?>
