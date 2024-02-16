<?php
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();
if ($_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/loginPage.html');
}
$userdata = $_SESSION['userdata'];
include("../../api/connect.php");
include("../../api/checkUserVote.php");
$userId = $userdata['Id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>
    <link rel="stylesheet" href="../../css/userPannel.css">
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <h1>Online Voting System</h1>
        <nav>
            <ul>
                <li><a href="personalInfo.php">Personal Info</a></li>
                <li><a href="" class='active'>Elections</a></li>
                <li><a href="">Results</a></li>
            </ul>
            <div class='welcome'>
                <h3><span id='user'><?php echo $userdata['Full_Name'] ?></span></h3>
                <a href='../../api/logout.php' id='logout'>LogOut</a>
            </div>
        </nav>
    </header>
    <div class="elections">
        <h3>Upcoming Elections : </h3>
        <hr>

        <?php
        $queryUpcomingElection = "SELECT * FROM election WHERE Status ='inactive'";
        $resultUpcomingElection = mysqli_query($connect, $queryUpcomingElection);
        echo "<div class='upcomingElection'>";
        while ($rowUpcomingElection = mysqli_fetch_assoc($resultUpcomingElection)) {
            echo "<section class='eid'>";
            echo "<span class='eTitle'>{$rowUpcomingElection['Title']} </span>";
            echo "<caption>{$rowUpcomingElection['StartDate']} </caption> <br/>";
            echo "</section>";
        }
        echo "</div>";
        ?>

        <h3>Ongoing Elections : </h3>
        <hr>
        <div id="user-data" data-user-id="<?php echo $userdata['Id']; ?>"></div>

        <div class="main">
            <div class="items">
                <?php
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
                                echo "<img src='../../uploads/{$rowCandidate['Image']}' alt='Candidate Image'>";
                                echo "</div>";
                                echo "<strong>Full Name: <span class='username'>{$rowCandidate['Full_Name']}</span></strong>";
                                echo "<small>Description:<span class='username'> {$rowCandidate['Description']}</span></small>";
                                echo "</div>"; // Close the candidate card here
                            }

                            echo "</div>";

                            if (!$hasVoted) {
                                // Display the voting section only if the user has not voted in this election
                                echo "<div class='voteSection'>";
                                echo "<form method='post' action='../../api/vote.php'>";
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
</body>

</html>