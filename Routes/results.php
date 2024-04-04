<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
    header('location: ../Routes/index.html');
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        $eDate = $rowElection['StartDate'];
        $eId = $rowElection['Id'];
        echo "<span class='eTitle'>Election Title: {$eTitle}</span>";
        echo "<span class ='eDate'> Election Date : {$eDate}</span>";
        echo "<h4>Candidates : </h4>";
        // Count the number of candidates for the current election
        $queryCandidatesNames = "SELECT Full_Name FROM candidate WHERE Position = '{$eTitle}'";
        $resultCandidateNames = mysqli_query($connect, $queryCandidatesNames);

        // Check for query execution errors
        if (!$resultCandidateNames) {
            die("Query execution failed: " . mysqli_error($connect));
        }

        // Fetch candidate names from the result set
        echo "<ol type='1'>";
        while ($row = mysqli_fetch_assoc($resultCandidateNames)) {
            echo "<li style='text-align:left;'> {$row['Full_Name']}</li>";
        }
        echo "</ol>";

        // Display total number of votes for the election
        $queryVoteCount = "SELECT COUNT(*) as totalVoteCount FROM votes WHERE ElectionId = '{$eId}'";
        $resultTotalVotes = mysqli_query($connect, $queryVoteCount);

        if ($resultTotalVotes) {
            $totalVoteCount = mysqli_fetch_assoc($resultTotalVotes);
            $count = $totalVoteCount['totalVoteCount'];
            echo "Total No Of Votes: {$count}";
        } else {
            echo "Error: " . mysqli_error($connect);
        }

        echo "<h4>Vote Counts:</h4>";
        echo "<table border='1' id='resultTable'>";
        echo "<tr><th>SN</th><th>Candidate Name</th><th>Vote Count</th><th>Vote Percentage</th></tr>";

        // Query to get vote counts and percentages for each candidate
        $queryCountEachCandidateVote = "SELECT c.Id AS CandidateId, c.Full_Name AS CandidateName, COUNT(v.CandidateId) AS VoteCount,
        (COUNT(v.CandidateId) / (SELECT COUNT(*) FROM votes WHERE ElectionId = '{$eId}')) * 100 AS VotePercentage
        FROM candidate c
        LEFT JOIN votes v ON c.Id = v.CandidateId
        WHERE v.ElectionId = '{$eId}'
        GROUP BY c.Id, c.Full_Name";

$result = mysqli_query($connect, $queryCountEachCandidateVote);

if ($result) {
    // Store the highest vote count
    $highestVoteCount = 0;
    // Array to store winner(s) in case of a tie
    $winners = [];
    $sn=1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>$sn</td>";
        $sn++;
        echo "<td>{$row['CandidateName']}</td>";
        echo "<td>{$row['VoteCount']}</td>";
        echo "<td>{$row['VotePercentage']}%</td>";
        echo "</tr>";

        // Update highest vote count
        if ($row['VoteCount'] > $highestVoteCount) {
            $highestVoteCount = $row['VoteCount'];
            // Clear previous winners if the new highest count is found
            $winners = [$row];
        } elseif ($row['VoteCount'] == $highestVoteCount) {
            // Add candidate to winners array in case of a tie
            $winners[] = $row;
        }
    }

    // Display winner(s)
    if (!empty($winners)) {
        echo "<p>Winner(s): ";
        foreach ($winners as $winner) {
            echo "{$winner['CandidateName']} ({$winner['VoteCount']} votes), ";
        }
        echo "</p>";
    } else {
        echo "<p>No winner declared.</p>";
    }
} else {
    echo "Error: " . mysqli_error($connect);
}

echo "</table>";
        echo "</div>";
    }
    ?>
</section>

        </main>


    </div>
    <script src="js/menu.js"></script>

</body>

</html>