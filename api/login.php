<?php
session_start();

include("connect.php");

$userRole = $_POST['role'];
$email = $_POST['email'];
$password = $_POST['password'];

//checking role
if ($userRole === 'admin') {
    $adminTable = 'admins';
    $sql = "SELECT * FROM $adminTable WHERE Email='$email' AND Password = '$password'";
    $result = mysqli_query($connect, $sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['userdata'] = $row;
        header("Location: ../Routes/adminPannel.php");
        exit;
    } else {
        echo "<script>
        alert('Invalid Id or Password,  try again !');
        window.history.back();
        </script>
        ";
    }
} else if ($userRole === 'user') {
    $table1 = 'validuser';
    $table2 = 'pendingusers';
    $sql = "SELECT * FROM $table1 WHERE Email = ? UNION SELECT * FROM $table2 WHERE Email = ?";

    // Use prepared statement
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                $_SESSION['userdata'] = $row;

                header("Location: ../Routes/userPart/personalInfo.php");
                exit;
            } else {
                echo "
                <script> 
                alert('Invalid credentials');
                window.history.back();
                </script>
                ";
                exit;
            }
        } else {
            echo "
            <script> 
            alert('Invalid credentials');
            window.history.back();
            </script>
            ";
            exit;
        }
    } else {
        // Handle query execution error
        echo "Query execution error: " . mysqli_error($connect);
    }
}

// Close the database connection
$connect->close();

?>