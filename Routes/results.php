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
    <div class="container">
        <?php include 'components/_header.php' ?>
        <main>
            <h2>Elections Results</h2>
            <hr>
            <section class='closedElections'>
                <?php
                $queryElection = "SELECT * FROM election WHERE Status='Closed'";
                $resultElection = mysqli_query($connect, $queryElection);
                while ($rowElection = mysqli_fetch_assoc($resultElection)) {
                    echo "<div class='result'> ";
                    $eTitle = $rowElection['Title'];
                    echo "<span class='eTitle'> Election Title : {$eTitle} </span>";

                    // Count the number of candidates for the current election
                    $queryCandidates = "SELECT COUNT(*) as count FROM candidate WHERE Position = '{$eTitle}'";
                    $resultNumberCandidates = mysqli_query($connect, $queryCandidates);
                    $rowCandidates = mysqli_fetch_assoc($resultNumberCandidates);
                    $rowCount = $rowCandidates['count'];
                    if ($rowCount > 0) {
                        echo "<span class= 'number'> Number of Candidates  = {$rowCount}</span>";

                        // Query to find the winner for the current election
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
                            echo "<span> Winner = {$rowResult['Full_Name']}</span>";
                            echo "<span> Number of Votes = {$winnerVotes}</span>";
                        } else {
                            echo "<h3>No winner yet</h3>";
                        }
                    } else {
                        echo "Number of Candidates  = 0";
                    }

                    echo "</div>";
                }
                ?>
            </section>

        </main>
    </div>
</body>

</html>