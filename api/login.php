<?php
session_start();
include("connect.php");

$userRole = $_POST['role'];
$email = $_POST['email'];
$password = $_POST['password'];

// Function to authenticate user
function authenticateUser($table, $email, $password, $redirectLocation)
{
    global $connect;
    $sql = "SELECT * FROM $table WHERE Email = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
            // Check if the provided password matches the hashed password
            if (password_verify($password, $row['Password'])) {
                $_SESSION['userdata'] = $row;
                header("Location: $redirectLocation");
                exit;
            }
    }

    // Authentication failed
    echo "<script>alert('Invalid credentials'); window.history.back();</script>";
    exit;
}
if ($userRole === "admin") {
    $table = 'admins';
    // admin
    authenticateUser($table, $email, $password, "../Routes/adminPannel.php");
} elseif ($userRole === 'user') {
    $table = 'users';
    // user
    authenticateUser($table, $email, $password, "../Routes/user/personalInfo.php");
}

// Close the database connection
$connect->close();
?>