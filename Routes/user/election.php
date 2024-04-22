<?php
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();

// Redirect if user is not logged in or doesn't have 'user' role
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/index.html');
    exit(); // Stop further execution
}

$userdata = $_SESSION['userdata'];
include("../../api/connect.php");
include("../../api/checkUserVote.php");
$userId = $userdata['Id'];

// Function to fetch elections based on status
function fetchElections($connect, $status)
{
    $query = "SELECT * FROM election WHERE Status ='$status'";
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Error fetching elections: " . mysqli_error($connect));
    }
    return $result;
}
function hasUpcomingElections($connect) {
    $query = "SELECT COUNT(*) AS count FROM election WHERE Status ='inactive'";
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Error fetching upcoming elections: " . mysqli_error($connect));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}
// Function to display election sections
function displayElectionSections($connect, $userId, $elections)
{
    while ($rowElection = mysqli_fetch_assoc($elections)) {
        echo "<section class='eid'>";
        echo "<span class='eTitle'>{$rowElection['Title']} </span>";
        echo "<caption>{$rowElection['StartDate']} </caption> <br/>";
        echo "</section>";
    }
}
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
<div class="mainContainer">

    <header>
        <h1>Online Voting System</h1>
        <?php include '../components/_nav.php' ?>
    </header>
    <div class="elections">
        <?php
        // Check if there are upcoming elections
        if (hasUpcomingElections($connect)) {
            // Fetch upcoming elections
            $resultUpcomingElection = fetchElections($connect, 'inactive');
            echo "<h3>Upcoming Elections :</h3>";
            echo "<hr>";
            echo "<div class='upcomingElection'>";
            displayElectionSections($connect, $userId, $resultUpcomingElection);
            echo "</div>";
        }
        ?>

        <h3>Ongoing Elections : </h3>
        <hr>
        <div id="user-data" data-user-id="<?php echo $userdata['Id']; ?>"></div>

        <div class="main">
            <div class="items">
                <?php
                // Fetch election titles
                $resultElection = fetchElections($connect, 'Ongoing');
                while ($rowElection = mysqli_fetch_assoc($resultElection)) {
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
                            echo "<img src='../../uploads/{$rowCandidate['Image']}' alt='Candidate Image' onerror=\"this.src='../../img/def.jpg'\">";
                            echo "</div>";
                            echo "<p>Full Name<br> <span class='username'>{$rowCandidate['Full_Name']}</span></p>";
                            echo "<p>Description:<br><span class='username'> {$rowCandidate['Description']}</span></p>";
                            echo "</div>";
                        }

                        echo "</div>";

                        if (!$hasVoted && ($userdata['Verified'] === 1)) {
                            // Display the voting section only if the user has not voted in this election
                            echo "<div class='voteSection'>";
                            echo "<form method='post' action='../../api/vote.php' id='{$rowElection['Title']}'>";
                            echo "<label for='candidateSelection' class='labelVote'>Vote : </label> ";
                            echo "<select class='candidateSelection' name='candidateSelection' required>";
                            echo "<option disabled> --Select A Candidate--</option>";
                            // Display candidate options in the dropdown
                            foreach ($candidatesArray as $rowCandidate) {
                                echo "<option value='" . $rowCandidate['Id'] . "'>" . $rowCandidate['Full_Name'] . "</option>";
                            }
                            echo "</select>";
                            echo "<input type='hidden' name='userId' value='$userId'>";
                            echo "<input type='hidden' name='electionId' value='$electionId'>";
                            echo "<button type='submit' class='voteBtn' onclick='return confirm(\"Are you sure?\")'>Submit</button>";
                            echo "</form>";                            
                            echo "</div>";

                        } else if ($userdata['Verified'] === 0) {
                            echo "<div class = 'pendinguser'> You are not a Valid voter ,yet </div>";
                        } else {
                            echo "<div class='alreadyVoted'>You have already voted in this election.</div>";
                        }

                        echo "</div>"; // Close the cardContainer only if there are candidates
                    }
                    mysqli_stmt_close($stmtCandidates);
                }

                // Close the database connection if necessary
                mysqli_close($connect);
                ?>

            </div>

        </div>
    </div>
<?php include '../components/_footer.php'; ?>
</div>

</body>

</html>