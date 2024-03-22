<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
    header('location: index.html');
    exit;
}

$userdata = $_SESSION['userdata'];

// Fetch user data from the database
include ('../api/connect.php');
$query = "SELECT * FROM users WHERE Verified = 1";
$result = mysqli_query($connect, $query);

// Check if the query was successful
if (!$result) {
    die ("Query failed: " . mysqli_error($connect));
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
    <?php include 'components/_sidebar.php' ?>

    <div class="container">
        <?php include './components/_header.php' ?>

        <nav>

            <div class="main">
                <div class="addVoter">
                    <h3>Voter List</h3>
                    <button class="more btn_more"> Add Voter</button>
                </div>
                <hr>
                <div class="table-container">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Full Name</th>
                                <th>Number</th>
                                <th>Age</th>
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
                                echo "<td><img class='user-image' src='../uploads/{$row['Image']}' alt='User Image'></td>";
                                echo "<td>{$row['Full_Name']}</td>";
                                echo "<td>{$row['Number']}</td>";
                                echo "<td>{$row['Age']}</td>";
                                echo "<td>{$row['Email']}</td>";
                                echo "<td>{$row['Address']}</td>";
                                echo "<td>{$row['Role']}</td>";
                                echo "<td>
        <form action='../api/process_action.php' method='post'>
            <input type='hidden' name='user_id' value='{$row['Id']}'>
            <input type='hidden' name='originating_page' value='voter'>
            <button type='submit' name='reject' class='reject'>Delete</button>
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
    <div class="addMore pop_box" id="modal">
        <form action="../api/addVoter.php" id="form" method="post" enctype="multipart/form-data" class="add">
            <h3>Registration</h3>
            <div class="cover">
                <input type="text" name="name" id="name" onkeyup="nameValid()" required
                    placeholder="Enter your Full Name " />
                <div class="messagejs namemsg"></div>
            </div>
            <label for="dob">DOB :</label>
            <input type="date" name="dob" id="dob" max="2010-01-01" required placeholder="Enter your date of birth" />
            <input type="hidden" name="age" id="age" readonly />
            <input type="hidden" name="age" readonly>
            <div class="cover">
                <input type="number" name="number" id="number" onkeyup="numberValid()" required
                    placeholder="Enter your phone number" />
                <div class="messagejs numbermsg"></div>
            </div>

            <div class="cover">
                <input type="password" name="password" id="password" onkeyup="passwordValid()"
                    placeholder="Enter new password" />
                <div class="messagejs passwordmsg"></div>
            </div>

            <input type="email" name="email" id="email" required placeholder="Enter your email" />

            <input type="text" name="address" id="address" required placeholder="Enter your address" />
            <br>
            <label for="image">Your Passport Size Photo :</label>
            <input type="file" name="image" id="image" class="uploadImage" accept=".jpg, .jpeg, .png" required />
            <div class="btns">
                <button type="submit" id="submit" class="submit">Submit</button>
                <button type="reset" class="cancel btn_cancel">Cancel</button>

            </div>
        </form>
        </form>
    </div>
    <script src="js/script.js"></script>
    <script src="js/menu.js"></script>
    <script src="js/registerValidation.js"></script>

</body>

</html>