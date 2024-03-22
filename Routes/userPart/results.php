<?php
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();

// Redirect to login page if the user is not logged in or doesn't have the 'user' role
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/index.html');
    exit;
}

$userdata = $_SESSION['userdata'];
include '../../api/connect.php';

function fetchElections($connect, $status)
{
    $query = "SELECT * FROM election WHERE Status ='$status'";
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Error fetching elections: " . mysqli_error($connect)); // Handle query execution error
    }
    return $result;
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
    <header>
        <h1>Online Voting System</h1>
        <nav>
            <ul>
                <li><a href="personalInfo.php">Personal Info</a></li>
                <li><a href="election.php">Elections</a></li>
                <li><a href="#" class='active'>Results</a></li>
            </ul>
            <div class='welcome'>
                <h3><span id='user'><?php echo $userdata['Full_Name'] ?></span></h3>
                <a href='../../api/logout.php' id='logout'>LogOut</a>
            </div>
        </nav>
    </header>
    <section class='resultData'>
        <h2>Election Results</h2>
        <hr>
        <section class=' closedElections'>
            <?php
            $queryElection = "SELECT * FROM election WHERE Status='Closed'";
            $resultElection = mysqli_query($connect, $queryElection);
            while ($rowElection = mysqli_fetch_assoc($resultElection)) {
                echo "<div class='result'> ";
                $eTitle = $rowElection['Title'];
                echo "<span class='eTitle'>Election Title: {$eTitle}</span>";

                // Count the number of candidates for the current election
                $queryCandidates = "SELECT COUNT(*) as count FROM candidate WHERE Position = '{$eTitle}'";
                $resultNumberCandidates = mysqli_query($connect, $queryCandidates);
                $rowCandidates = mysqli_fetch_assoc($resultNumberCandidates);
                $rowCount = $rowCandidates['count'];
                if ($rowCount > 0) {
                    echo "<span class='number'>Number of Candidates: {$rowCount}</span>";

                    // Display winner information
                    $queryWinner = "SELECT CandidateId, COUNT(CandidateId) AS RepeatCount
                    FROM votes
                    WHERE ElectionId = '{$rowElection['Id']}'
                    GROUP BY CandidateId
                    ORDER BY RepeatCount DESC
                    LIMIT 1";
                    $resultWinner = mysqli_query($connect, $queryWinner);
                    $winnerData = mysqli_fetch_assoc($resultWinner);
                    if ($winnerData) {
                        $winnerId = $winnerData['CandidateId'];
                        $winnerVotes = $winnerData['RepeatCount'];
                        $candidateNameQuery = "SELECT Full_Name FROM candidate WHERE Id='{$winnerId}'";
                        $resultCandidateName = mysqli_query($connect, $candidateNameQuery);
                        $rowResult = mysqli_fetch_assoc($resultCandidateName);
                        echo "<span>Winner: {$rowResult['Full_Name']}</span>";
                        echo "<span>Number of Votes: {$winnerVotes}</span>";

                        // Display vote percentage
                        $totalVotesQuery = "SELECT COUNT(*) as totalVotes FROM votes WHERE ElectionId = '{$rowElection['Id']}'";
                        $resultTotalVotes = mysqli_query($connect, $totalVotesQuery);
                        $totalVotesData = mysqli_fetch_assoc($resultTotalVotes);
                        $totalVotes = $totalVotesData['totalVotes'];
                        $percentage = ($winnerVotes / $totalVotes) * 100;
                        echo "<span>Vote Percentage: {$percentage}%</span>";
                    } else {
                        echo "<h3>No winner yet</h3>";
                    }

                    // Display vote counts for each candidate
                    echo "<h3>Candidate Votes:</h3>";
                    $candidateQuery = "SELECT candidate.*, COUNT(votes.CandidateId) as voteCount
                    FROM candidate
                    LEFT JOIN votes ON candidate.Id = votes.CandidateId
                    WHERE candidate.Position = '{$eTitle}'
                    GROUP BY candidate.Id";
                    $resultCandidates = mysqli_query($connect, $candidateQuery);
                    while ($candidate = mysqli_fetch_assoc($resultCandidates)) {
                        echo "<p>{$candidate['Full_Name']}: {$candidate['voteCount']} votes</p>";
                    }
                } else {
                    echo "Number of Candidates: 0";
                }

                echo "</div>";
            }
            ?>
        </section>
    </section>
</body>

</html>