<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
    header('location: loginPage.html');
    exit;
}
include("../api/connect.php");
$userdata = $_SESSION['userdata'];

// Fetch user data from the database
$query = "SELECT * FROM pendingusers";
$result = mysqli_query($connect, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SecureVote - Online Voting Platform</title>
    <link rel="stylesheet" href="../css/Home.css" />
</head>

<body>
    <div class="container">
        <?php include './components/_header.php' ?>

        <nav>

            <div class="main">
                <h3>Pending User List</h3>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Full Name</th>
                                <th>Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['Id']}</td>";
                                echo "<td class='img-wrapper'><img class='user-image' src='../uploads/{$row['Image']}' alt='User Image'></td>";
                                echo "<td>{$row['Full_Name']}</td>";
                                echo "<td>{$row['Number']}</td>";
                                echo "<td>{$row['Email']}</td>";
                                echo "<td>{$row['Address']}</td>";
                                echo "<td>{$row['Status']}</td>";
                                echo "<td>
                        <form action='../api/process_action.php' method='post' class='btns'>
                            <input type='hidden' name='user_id' value='{$row['Id']}'>
                            <button type='submit' name='accept' class='accept'>Accept</button>
                            <input type='hidden' name='originating_page' value='pendingVoter'>
                            <button type='submit' name='reject' class='reject'>Reject</button>
                        </form>
                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </nav>
    </div>

    <script src="js/script.js"></script>
</body>

</html>
<?php
// Close the database connection
mysqli_close($connect);
?>