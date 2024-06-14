<?php
include '../../api/connect.php';
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();

// Redirect to login page if the user is not logged in or doesn't have the 'user' role
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/index.html');
    exit;
}

$userdata = $_SESSION['userdata'];
$userId = $userdata['Id'];
$userName = $userdata['Full_Name'];
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
    <?php include '../components/_sidebar2.php'; ?>

    <div class="mainContainer">
    <header>
        <h1>Online Voting System</h1>
        <?php include '../components/_nav.php' ?>
    </header>
    <section class='resultData'>
        <h3>Election Results</h3>
        <hr>
        <section class='closedElections'>
            <?php
            // Query to get results for published elections
            $queryElection = "SELECT * FROM results WHERE Published='1' GROUP BY Election_Id ORDER BY Election_Title";
            $resultElection = mysqli_query($connect, $queryElection);

            if ($resultElection) {
                while ($rowElection = mysqli_fetch_assoc($resultElection)) {
                    $eId = $rowElection['Election_Id'];
                    echo "<div class='wrapper'>";
                    echo "<span> Election Title: <span class='eTitle'>{$rowElection['Election_Title']}</span></span>";
                    echo "<p> Election Date: <span class='stDate'>{$rowElection['Election_Date']}</span></p>";
                    echo "<h4>Candidates:</h4>";

                    echo "<ol>";

                    // Query candidates for this election
                    $queryCandidates = "SELECT * FROM results WHERE Election_Id = {$rowElection['Election_Id']} AND Published = '1' ORDER BY Position ASC";
                    $resultCandidates = mysqli_query($connect, $queryCandidates);

                    // Display candidate names
                    $sn = 1;
                    while ($candidateRow = mysqli_fetch_assoc($resultCandidates)) {
                        echo "<li>$sn. {$candidateRow['Candidate_Name']}</li>";
                        $sn++;
                    }

                    echo "</ol>";

                    // Reset the result set to the beginning
                    mysqli_data_seek($resultCandidates, 0);
                    echo "<h4> Details : </h4>";
                    $queryVoteCount = "SELECT COUNT(*) as totalVoteCount FROM votes WHERE ElectionId = '{$eId}'";
                    $resultTotalVotes = mysqli_query($connect, $queryVoteCount);
            
                    if ($resultTotalVotes) {
                        $totalVoteCount = mysqli_fetch_assoc($resultTotalVotes);
                        $count = $totalVoteCount['totalVoteCount'];
                        echo "<p>Total No Of Votes: <span class='bold'>{$count}</span></p>";
                    } else {
                        echo "Error: " . mysqli_error($connect);
                    }
                    $queryWinner = "SELECT * FROM results WHERE Election_Id = {$rowElection['Election_Id']} AND Published = '1' ORDER BY Position ASC LIMIT 1";
                    $resultWinner = mysqli_query($connect, $queryWinner);
                    if ($rowWinner = mysqli_fetch_assoc($resultWinner)) {
                        echo "<span class= 'flex'>Winner: <p class='winner'> " . $rowWinner['Candidate_Name'] . " ( " . $rowWinner['Candidate_Votes'] . " Votes )</p></span>";
                    }
                    echo "<hr>";
                    // Display candidate information table
                    echo "<table border='1' id='resultTable'>
                            <tr>
                                <th>Position</th>
                                <th>Image</th>
                                <th>Candidate Name</th>
                                <th>Vote Count</th>
                                <th>Vote Percentage</th>
                            </tr>";

                    while ($candidateRow = mysqli_fetch_assoc($resultCandidates)) {
                        echo "<tr>";
                        echo "<td>{$candidateRow['Position']}</td>";
                        echo "<td><img src='../{$candidateRow['Candidate_Image']}' class='user-image' onerror=\"this.src='../../img/def.jpg'\"/></td>";
                        echo "<td>{$candidateRow['Candidate_Name']}</td>";
                        echo "<td>{$candidateRow['Candidate_Votes']}</td>";
                        echo "<td>{$candidateRow['Percentage']} %</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    echo "<button id='feedback_btn' class='setting'>Feedback</button>";
                    ?>
                    <div class="feedback_container pop_box2">
                        <h3>Feedback</h3>
                        <hr>
                        <form action="../../api/feedback.php" method="post">
                            <input type="hidden" name="eid" value='<?php echo $eId; ?>'>
                            <input type="hidden" name="uid" value='<?php echo $userId; ?>'>
                            <input type="hidden" name="user_name" value='<?php echo $userName; ?>'>
                            <input type="hidden" name="etitle" value='<?php echo $rowElection['Election_Title']; ?>'>
                            <textarea rows='4' placeholder='thank you..' cols='' name="feedback" id="feedback"
                                      required></textarea>
                            <input type="submit" value="submit" id='feedback_submit'
                                   onclick="return confirm('Are you Sure?');">
                            <input type="reset" class='btn_cancel2' value="Cancel">

                        </form>
                    </div>
                    <?php
                    echo "</div>";
                }
            } else {
                echo "Error fetching results: " . mysqli_error($connect);
            }
            ?>
        </section>
    </section>
</div>

    <?php include '../components/_footer.php'; ?>

    <script src="../js/sidebar.js"></script>
    <script src="../js/feedback_box.js"></script>

</body>

</html>