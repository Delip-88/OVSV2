<?php
include("connect.php");


if (isset($_POST['accept'])) {
    // Handle accept action
    $user_id = $_POST['user_id'];

    // Fetch user data
    $query = "SELECT * FROM users WHERE Id = $user_id";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // Verified = true
        $update_query = "UPDATE users SET Verified= 1 WHERE Id= $user_id";

        mysqli_query($connect, $update_query);
    }

    header("Location: ../Routes/pendingVoter.php"); // Redirect to the same page after action
    exit;
} else if (isset($_POST['reject'])) {
    // Handle reject action
    $user_id = $_POST['user_id'];
    $electionTitle = $_POST['Title'];
    $electionId = $_POST['eId'];
    $originating_page = $_POST['originating_page'];

    switch ($originating_page) {
        case 'pendingVoter':
            deleteImageAndRow($connect, $user_id, 'users', "../uploads/", "../Routes/pendingVoter.php");
            break;

        case 'voter':
            deleteImageAndRow($connect, $user_id, 'users ', "../uploads/", "../Routes/voter.php");
            break;

        case 'candidate':
            deleteImageAndRow($connect, $user_id, 'candidate', "../uploads/", "../Routes/candidate.php");
            break;

        case 'election':
            // code changed
            $election_delete_query = "DELETE FROM election WHERE Id = $user_id";
            if (mysqli_query($connect, $election_delete_query)) {
                echo "Deletion Success";
            } else {

                echo "Deletion Failed" . mysqli_error($connect);
            }
            $votes_delete_query = "DELETE FROM votes WHERE ElectionId= $electionId";
            mysqli_query($connect, $votes_delete_query);
            $delete_candidates_query = "DELETE FROM candidate WHERE Position='$electionTitle'";
            mysqli_query($connect, $delete_candidates_query);
            header("Location: ../Routes/position.php");
            break;

        default:
            // Default redirect if originating_page is not recognized
            header("Location: ../Routes/loginPage.php");
            break;
    }
}
function deleteImageAndRow($connect, $user_id, $table, $imagePathPrefix, $redirectPage)
{
    // Fetch the image name from the database
    $image_query = "SELECT Image FROM $table WHERE Id = $user_id";
    $image_result = mysqli_query($connect, $image_query);

    if ($image_result && $image_row = mysqli_fetch_assoc($image_result)) {
        $image_filename = $image_row['Image'];

        // Delete the image file from the server
        $image_path = $imagePathPrefix . $image_filename;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete the row from the specified table
    $election_delete_query = "DELETE FROM $table WHERE Id = $user_id";
    mysqli_query($connect, $election_delete_query);

    // Redirect to the specified page
    header("Location: $redirectPage");
    exit;
}

?>