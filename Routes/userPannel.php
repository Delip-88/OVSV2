<?php
$timeout = 24 * 60 * 60; //24 hours in seconds
session_set_cookie_params($timeout);
session_start();
if ($_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../Routes/loginPage.html');
}
$userdata = $_SESSION['userdata'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="../css/userPannel.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
</head>

<body>

    <header>
        <figure>
            <img src="../img/use.png" alt="" />
        </figure>
        <h1 id="title">SecureVote - Online Voting Platform</h1>
        <div class="welcome">
            <h3>Welcome, <span id="user">
                    <?php echo $userdata['Full_Name']; ?>
                </span></h3>
            <a href="../api/logout.php" id="logout">LogOut</a>
        </div>
    </header>
    <div class="container">
        <div class="card">
            <div class="img">
                <img src="../uploads/<?php echo $userdata['Image'] ?>" alt="">
            </div>
            <hr>
            <div class="info">
                <p>Name : <span class="Name"><?php echo $userdata['Full_Name'] ?></span></p>
                <p>Number : <span class="Number"><?php echo $userdata['Number'] ?></span></p>
                <p>Address : <span class="Address"><?php echo $userdata['Address'] ?></span></p>
                <p>status : <span class="Status"><?php echo $userdata['Role'] ?></span></p>
            </div>
            <i class="fa-solid fa-gear setting"></i>
            <i class="fa-regular fa-pen-to-square btn_more"></i>
        </div>
        <div class="elections">
            <h3>Current Elections</h3>
            <hr>
            <div id="user-data" data-user-id="<?php echo $userdata['Id']; ?>"></div>

            <div class="main">
                <div class="items">
                    <?php
                    include("../api/connect.php");
                    include("../api/checkUserVote.php");

                    $userId = $userdata['Id'];
                    // Fetch election titles
                    $queryElection = "SELECT Id, Title, Status FROM election";
                    $resultElection = mysqli_query($connect, $queryElection);

                    while ($rowElection = mysqli_fetch_assoc($resultElection)) {
                        // Display election title
                        if ($rowElection['Status'] === 'Ongoing') {
                            $electionId = $rowElection['Id'];

                            // Fetching election title
                            $queryCandidates = "SELECT * FROM candidate WHERE Position = ?";
                            $stmtCandidates = mysqli_prepare($connect, $queryCandidates);
                            mysqli_stmt_bind_param($stmtCandidates, 's', $rowElection['Title']);
                            mysqli_stmt_execute($stmtCandidates);
                            $resultCandidates = mysqli_stmt_get_result($stmtCandidates);

                            // Store candidates in an array
                            $candidatesArray = [];
                            while ($rowCandidate = mysqli_fetch_assoc($resultCandidates)) {
                                $candidatesArray[] = $rowCandidate;
                            }

                            // Check if the user has already voted in this election
                            $hasVoted = checkUserVote($connect, $userId, $electionId);

                            // Check if there are candidates before displaying the container
                            if (!empty($candidatesArray)) {
                                echo "<div class='cardContainerCover'>";
                                echo "<h2>Title : <span class='title'>{$rowElection['Title']} </span> </h2>";

                                echo "<div class='cardContainer'>";
                                // Display candidate information
                                foreach ($candidatesArray as $rowCandidate) {
                                    echo "<div class='eCard' data-election-id='{$electionId}' data-candidate-id='{$rowCandidate['Id']}'>";
                                    echo "<div class='user-image'>";
                                    echo "<img src='../uploads/{$rowCandidate['Image']}' alt='Candidate Image'>";
                                    echo "</div>";
                                    echo "<strong>Full Name: <span class='username'>{$rowCandidate['Full_Name']}</span></strong>";
                                    echo "<small>Description:<span class='username'> {$rowCandidate['Description']}</span></small>";
                                    echo "</div>"; // Close the candidate card here
                                }

                                echo "</div>";

                                if (!$hasVoted) {
                                    // Display the voting section only if the user has not voted in this election
                                    echo "<div class='voteSection'>";
                                    echo "<form method='post' action='../api/vote.php'>";
                                    echo "<label for='candidateSelection'>Vote : </label> ";
                                    echo "<select class='candidateSelection' name='candidateSelection' required>";
                                    echo "<option  disabled > --Select A Candidate --</option>";
                                    // Display candidate options in the dropdown
                                    foreach ($candidatesArray as $rowCandidate) {
                                        echo "<option value='" . $rowCandidate['Id'] . "' >" . $rowCandidate['Full_Name'] . "</option>";
                                    }
                                    echo "</select>";
                                    echo "<input type='hidden' name='userId' value='$userId'>";
                                    echo "<input type='hidden' name='electionId' value='$electionId'>";
                                    echo "<button type='submit' class='voteBtn'>Submit</button>";
                                    echo "</form>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='alreadyVoted'>You have already voted in this election.</div>";
                                }

                                echo "</div>"; // Close the cardContainer only if there are candidates
                            }

                            mysqli_stmt_close($stmtCandidates);
                        }
                    }

                    // Close the database connection if necessary
                    mysqli_close($connect);
                    ?>

                </div>

            </div>
        </div>
    </div>

    <!-- POP-UP FORUM -->
    <div class="pop_box" id="modal">
        <h2>Edit</h2>
        <small id='error'></small>

        <hr>
        <form action="../api/updateDetails.php" method="post" class="pos" id="adC">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value='<?php echo $userdata['Full_Name'] ?>'>
            <br>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value='<?php echo $userdata['Address'] ?>'>
            <br>
            <label for="number">Number:</label>
            <input type="text" name="number" id="number" value='<?php echo $userdata['Number'] ?>'>
            <br>
            <div class="btns">
                <input type="hidden" name="uid" value="<?php echo $userdata['Id'] ?>">
                <button type="submit" id="btnC">Apply Changes</button>
                <button type="reset" class="cancel btn_cancel">cancel</button>
            </div>
        </form>
    </div>
    <div class="pop_box2" id="">
        <h2>Edit</h2>
        <hr>
        <form action="../api/updateDetails.php" method="post" class="pos" id="form">
            <br>
            <label for="password">Current Pasword:</label>
            <input type="text" name="password" id="password" required>
            <br>
            <br>
            <label for="newPassword">New Password:</label>
            <input type="text" name="newPassword" id="newPassword" required>
            <br>
            <br>
            <label for="rePassword">Confirm Password:</label>
            <input type="text" name="rePassword" id="rePassword" required>
            <br>
            <br>
            <div class="btns">
                <input type="hidden" name="uid" value="<?php echo $userdata['Id'] ?>">
                <button type="submit" id="btnC">Apply Changes</button>
                <button type="reset" class="cancel btn_cancel2">cancel</button>
            </div>
        </form>
    </div>
    <script src="js/script.js"></script>
    <script src="js/userScript.js"></script>
    <script defer src="js/passwordValidation.js"></script>
</body>

</html>