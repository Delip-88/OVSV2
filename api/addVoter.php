<?php
include ("connect.php");

function showAlertAndGoBack($message)
{
    echo "<script>
    alert('$message'); 
    window.history.back();
    </script>";
    exit;
}

function isEmailAlreadyRegistered($connect, $email)
{
    $query = "SELECT COUNT(*) as count FROM users WHERE email = ? ";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $totalCount = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $totalCount += $row['count'];
    }

    mysqli_stmt_close($stmt);

    return $totalCount > 0;
}

$name = $_POST['name'];
$dob = $_POST['dob'];
$age = $_POST['age'];
$number = $_POST['number'];
$password = $_POST['password'];
$email = $_POST['email'];
$address = $_POST['address'];
$image = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];

$emailToCheck = $email;

if (isEmailAlreadyRegistered($connect, $emailToCheck)) {
    showAlertAndGoBack('Email already registered. Please use a different email.');
}

// Move uploaded file to the destination directory
move_uploaded_file($tmp_name, "../uploads/$image");

// Use prepared statement to prevent SQL injection
$hash = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users(Full_Name,DOB,Age, Number, Password, Email, Address, Image, Role, Verified) VALUES (?,?,?, ?, ?, ?, ?, ?, 'user', 1)";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'ssssssss', $name, $dob, $age, $number, $hash, $email, $address, $image);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "<script>alert('Registration successful'); 
    window.location.href='../Routes/voter.php';
    </script>";
} else {
    echo "Some error occurred";
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>