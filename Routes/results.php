<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
    header('location: ../Routes/loginPage.html');
    exit;
}

include('../api/connect.php');

$userdata = $_SESSION['userdata'];
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
        <?php include 'components/_header.php' ?>
        <main>
            <h2>Election Results</h2>
            <hr>
            <section class='closedElections'>
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
        </main>


    </div>
    <script src="js/menu.js"></script>

</body>

</html>