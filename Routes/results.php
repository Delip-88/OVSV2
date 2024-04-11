<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
    header('location: ../Routes/index.html');
    exit;
}

include ('../api/connect.php');

$userdata = $_SESSION['userdata'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SecureVote - Online Voting Platform</title>
    <link rel="stylesheet" href="../css/Home.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
        
        echo "<span style='display:none'> Election Id:<span class='eId' > {$eId}</span></span>";
        echo "<span> Election Title: <span class='eTitle'>{$eTitle}</span></span>";
        echo "<span>  Election Date : <span class ='eDate'>{$eDate}</span></span>";
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
        $num = 1;
        while ($row = mysqli_fetch_assoc($resultCandidateNames)) {
            echo "<li style='text-align:left;'>$num. {$row['Full_Name']}</li>";
            $num++;
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
        echo "<tr><th>SN</th><th>Image</th><th>Candidate Name</th><th>Vote Count</th><th>Vote Percentage</th></tr>";

        // Query to get vote counts and percentages for each candidate
        $queryCountEachCandidateVote = "SELECT c.Id AS CandidateId, c.Full_Name AS CandidateName, c.Image AS CandidateImage, 
        COUNT(v.CandidateId) AS VoteCount,
        ROUND((COUNT(v.CandidateId) / (SELECT COUNT(*) FROM votes WHERE ElectionId = '{$eId}')) * 100, 2) AS VotePercentage
        FROM candidate c
        LEFT JOIN votes v ON c.Id = v.CandidateId
        WHERE v.ElectionId = '{$eId}'
        GROUP BY c.Id, c.Full_Name, c.Image";
    
    

        $result = mysqli_query($connect, $queryCountEachCandidateVote);

        if ($result) {
            // Store the highest vote count
            $highestVoteCount = 0;
            // Array to store winner(s) in case of a tie
            $winners = [];
            $sn = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>$sn</td>";
                $sn++;
                echo "<td data-imagepath='../uploads/{$row['CandidateImage']}'><img class='user-image' src='../uploads/{$row['CandidateImage']}'alt='User Image'></td>";
                echo "<td>{$row['CandidateName']}</td>";
                echo "<td data-votecount='{$row['VoteCount']}'>{$row['VoteCount']}</td>";
                echo "<td data-percentage='{$row['VotePercentage']}%'>{$row['VotePercentage']}%</td>";
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
        echo "<div class='btns'>";
        echo "<button class='publishResult' onclick='return confirm(\"Are you sure?\")'> Publish Results </button> ";
        echo "<form method='post' action='../api/process_action.php'>";
        echo "<input type='hidden' name='eId' value='{$eId}'>";
        echo "<input type='hidden' name='Title' value='{$eTitle}'>";
        echo "<input type='hidden' name='user_id' value='admin'>";
        echo "<input type='hidden' name='originating_page' value='result'>";
        echo "<button type='submit' name='reject' class='deleteResult' onclick='return confirm(\"Are you sure?\")'>Delete Result </button>";
        echo "</form>";
        echo "</div>";

        echo "</div>";
    }
    ?>
</section>


        </main>

    </div>
    <script src="js/menu.js"></script>
    <script src="js/publishResults.js"></script>
    

</body>

</html>