<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the submitted form
    $userId = $_POST['userId'];
    $electionId = $_POST['electionId'];
    $selectedCandidateId = $_POST['candidateSelection'];

    // Check if the user has already voted in this election
    $queryCheckVote = "SELECT COUNT(*) as count FROM votes WHERE UserId = ? AND ElectionId = ?";
    $stmtCheckVote = mysqli_prepare($connect, $queryCheckVote);
    mysqli_stmt_bind_param($stmtCheckVote, 'ii', $userId, $electionId);
    mysqli_stmt_execute($stmtCheckVote);
    $resultCheckVote = mysqli_stmt_get_result($stmtCheckVote);
    $rowCheckVote = mysqli_fetch_assoc($resultCheckVote);

    if ($rowCheckVote['count'] > 0) {
        // User has already voted in this election
        header("Location: ../Routes/user/election.php?message=already_voted");
        exit(); // Stop further execution
    } else {
        // Insert the vote into the 'votes' table
        $queryInsertVote = "INSERT INTO votes (UserId, ElectionId, CandidateId) VALUES (?, ?, ?)";
        $stmtInsertVote = mysqli_prepare($connect, $queryInsertVote);
        mysqli_stmt_bind_param($stmtInsertVote, 'iii', $userId, $electionId, $selectedCandidateId);

        if (mysqli_stmt_execute($stmtInsertVote)) {
            header("Location: ../Routes/user/election.php?message=vote_recorded");
            exit(); // Stop further execution
        } else {
            header("Location: ../Routes/user/election.php?message=vote_error");
            exit(); // Stop further execution
        }
    }

}

// Close the database connection
mysqli_close($connect);
?>