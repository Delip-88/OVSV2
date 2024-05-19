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
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'components/_sidebar.php' ?>


    <div class="container">
        <?php include 'components/_header.php' ?>
        <nav>
            <div class="main">
                <div class="main-container">
                    <div class="currentElections">
                        <h2>Overview</h2>
                        <hr>
                        <div class="overview">
                            <p>Number Of Ongoing Elections: <span>
                                    <?php
                                    $queryCount = "SELECT COUNT(*) as count FROM election WHERE Status='Ongoing'";
                                    $resultquerycount = mysqli_query($connect, $queryCount);

                                    if ($resultquerycount) {
                                      $erow = mysqli_fetch_assoc($resultquerycount);
                                      $erowCount = $erow['count'];
                                      echo $erowCount;
                                    } else {
                                      echo "Error fetching data: " . mysqli_error($connect);
                                    }
                                    ?>
                                </span></p>
                        </div>
                        <h2>Ongoing Elections : </h2>
                        <hr>
                        <?php

                        //Dispaly Ongoing elections
                        $queryElection = "SELECT Id,Title,Status FROM election WHERE Status='Ongoing'";
                        $resultElection = mysqli_query($connect, $queryElection);

                        while ($rowElection = mysqli_fetch_assoc($resultElection)) {
                          echo "<div class='currentElectionBox'>";
                          echo "<h3>Title : <span class='ongoingElectionName'>" . $rowElection["Title"] . "</span></h3>";

                          //Display election candidates
                          $queryCandidates = "SELECT Id,Full_Name,Image FROM candidate WHERE Position='{$rowElection['Title']}'";
                          $resultCandidates = mysqli_query($connect, $queryCandidates);
                          echo "<small>Candidates : </small>";
                          echo "<div class='candidateCardCover'>";
                          while ($rowCandidates = mysqli_fetch_assoc($resultCandidates)) {
                            echo "<div class='candidateCard'>";
                            echo "<div class='userImage'>";
                            echo "<img src='../uploads/Candidate-Image/{$rowCandidates['Image']}' alt='Candidate Image' class='user-image' onerror=\"this.src='../img/def.jpg'\">";
                            echo "</div>";
                            echo "<strong>" . $rowCandidates['Full_Name'] . "</strong></p>";

                            //Vote count from vote db
                            $queryVoteCount = "SELECT COUNT(*) as count FROM votes WHERE ElectionId='{$rowElection['Id']}' AND CandidateId='{$rowCandidates['Id']}'";
                            $resultVoteCount = mysqli_query($connect, $queryVoteCount);
                            $row = mysqli_fetch_assoc($resultVoteCount);
                            $rowCount = $row['count'];
                            echo "<p>Number of Votes : <strong>" . $rowCount . "</strong></p>";
                            echo "</div>";
                          }
                          echo "</div>";


                          echo "</div>";
                        }
                        ?>

                        <div class="candidates"></div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <script src="js/sidebar.js"></script>
</body>

</html>