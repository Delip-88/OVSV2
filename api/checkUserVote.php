<?php
function checkUserVote($connect, $userId, $electionId)
{
    $query = "SELECT COUNT(*) as count FROM votes WHERE UserId = ? AND ElectionId = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $electionId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // If the count is greater than 0, the user has voted in this election
    return $row['count'] > 0;
}
?>
