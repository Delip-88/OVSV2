<?php
include("connect.php");

function showAlertAndGoBack($message)
{
    echo "<script>
    alert('$message'); 
    window.history.back();
    </script>";
    exit;
}

function validatePassword($password)
{
    return strlen($password) >= 8;
}

function validateNumber($number)
{
    return strlen($number) === 10 && ctype_digit($number);
}

function isEmailAlreadyRegistered($connect, $email)
{
    $query = "SELECT COUNT(*) as count FROM pendingusers WHERE email = ? UNION SELECT COUNT(*) as count FROM validuser WHERE email = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $email);
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
$number = $_POST['number'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$email = $_POST['email'];
$address = $_POST['address'];
$image = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];
$role = $_POST['role'];

$emailToCheck = $email;

if (isEmailAlreadyRegistered($connect, $emailToCheck)) {
    showAlertAndGoBack('Email already registered. Please use a different email.');
}

if (!validatePassword($password, )) {
    showAlertAndGoBack('At least 8 characters required for the password.');
}

if (!validateNumber($number)) {
    showAlertAndGoBack('Error: Number must be exactly 10 digits.');
}

if ($password !== $cpassword) {
    showAlertAndGoBack("Confirm Password doesn\'t Match Password");
}

// Move uploaded file to the destination directory
move_uploaded_file($tmp_name, "../uploads/$image");

// Use prepared statement to prevent SQL injection
$hash = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO pendingusers(Full_Name, Number, Password, Email, Address, Image, Role, Status, Votes) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 0)";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'sssssss', $name, $number, $hash, $email, $address, $image, $role);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "<script>alert('Insertion successful'); 
    window.location.href='../Routes/loginPage.html';
    </script>";
} else {
    echo "Some error occurred";
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>