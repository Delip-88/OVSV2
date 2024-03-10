<?php
session_start();
include("connect.php");

$userRole = $_POST['role'];
$email = $_POST['email'];
$password = $_POST['password'];

// Function to authenticate user
function authenticateUser($table, $email, $password, $redirectLocation, $hashPassword = false)
{
    global $connect;
    $sql = "SELECT * FROM $table WHERE EMAIL = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($hashPassword) {
            // Check if the provided password matches the hashed password
            if (password_verify($password, $row['Password'])) {
                $_SESSION['userdata'] = $row;
                header("Location: $redirectLocation");
                exit;
            }
        } else {
            // Directly compare passwords without hashing
            if ($password === $row['Password']) {
                $_SESSION['userdata'] = $row;
                header("Location: $redirectLocation");
                exit;
            }
        }
    }

    // Authentication failed
    echo "<script>alert('Invalid credentials'); window.history.back();</script>";
    exit;
}

if ($userRole === "admin") {
    $table = 'admins';
    // No password hashing for admin password
    authenticateUser($table, $email, $password, "../Routes/adminPannel.php");
} elseif ($userRole === 'user') {
    $table = 'users';
    // Password hashing for user password
    authenticateUser($table, $email, $password, "../Routes/userPart/personalInfo.php", true);
}

// Close the database connection
$connect->close();
?>