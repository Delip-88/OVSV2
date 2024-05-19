<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cName = $_POST['name'];
    $position = $_POST['pos'];
    $img = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $des = $_POST['description'];

    if (!empty($img)) {
        move_uploaded_file($tmp_name, "../uploads/Candidate-Image/$img");

        // Use prepared statement to prevent SQL injection
        $insert = mysqli_prepare($connect, "INSERT INTO candidate(Full_Name, Position, Image, Description) VALUES(?, ?, ?, ?)");

        mysqli_stmt_bind_param($insert, 'ssss', $cName, $position, $img, $des);

        if (mysqli_stmt_execute($insert)) {
            echo "
            <script>
            window.location.href='../Routes/candidate.php'
            </script>";
        } else {
            echo "Some error occurred";
        }

        mysqli_stmt_close($insert);
    } else {
        echo "Please select an image.";
    }
}
?>