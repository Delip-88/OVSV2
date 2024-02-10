<?php
session_start();

include("connect.php");

$userRole = $_POST['role'];
$email = $_POST['email'];
$password = $_POST['password'];

//checking role
if ($userRole === 'admin') {
    $table = 'admins';
} else if ($userRole === 'user') {
    $table = 'validuser';
} else {
    echo "Invalid role specified";
    exit; // Exit the script if an invalid role is specified
}

//Authenticate by querying from database
$sql = "SELECT * FROM $table WHERE Email='$email'";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Fetch the first row only
    if ($userRole === 'admin') {
        $_SESSION['userdata'] = $row;
        header('Location: ../Routes/adminPannel.php');
        exit;
    } else if ($userRole === 'user') {
        if (password_verify($password, $row['Password'])) {
            $_SESSION['userdata'] = $row;
            header('Location: ../Routes/userPannel.php');
            exit;
        } else {
            echo "
            <script>
            alert('Invalid Credentials');
            window.history.back();
            </script>
            ";
            exit;
        }
    }
} else {
    echo "
    <script>
    alert('Invalid Credentials');
    window.history.back();
    </script>
    ";
    exit;
}
?>
