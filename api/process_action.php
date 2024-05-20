<?php
include ("connect.php");


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
            deleteImageAndRow($connect, $user_id, 'candidate', "../uploads/Candidate-Image/", "../Routes/candidate.php");
            break;
        case 'feedback':
            $queryDeleteFeedback = "DELETE FROM feedback WHERE Id = ?";
            if ($stmt = mysqli_prepare($connect, $queryDeleteFeedback)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../Routes/feedback.php");
                } else {
                    echo "Error Deleting Feedback: " . mysqli_error($connect);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Failed to prepare statement: " . mysqli_error($connect);
            }
            break;
        case 'result':
            $queryDeleteVotes = "DELETE FROM votes WHERE ElectionId = $electionId";
            $queryDeleteElection = "DELETE FROM election WHERE Id = $electionId";
            $queryDeleteResults = "DELETE FROM results WhERE Election_Id = $electionId";

            // Delete the election record
            if (mysqli_query($connect, $queryDeleteElection) && mysqli_query($connect, $queryDeleteVotes) && mysqli_query($connect, $queryDeleteResults)) {

                // Delete candidate images associated with the election
                $queryImage = "SELECT Image FROM candidate WHERE Position = '$electionTitle'";
                $result = mysqli_query($connect, $queryImage);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $imgFileName = $row['Image'];
                        $image_path = "../uploads/Candidate-Image/" . $imgFileName;
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                }
                // Delete candidates associated with the election
                $delete_candidates_query = "DELETE FROM candidate WHERE Position = '$electionTitle'";
                mysqli_query($connect, $delete_candidates_query);
                // Redirect to the position page
                header("Location: ../Routes/results.php");
            } else {
                // Deletion of election record failed
                echo "Deletion Failed: " . mysqli_error($connect);
            }
            break;

        case 'election':
            // Check if data of election already exist at result table
            $check_result = "SELECT * FROM results WHERE Election_Id = '$electionId'";
            $result = mysqli_query($connect, $check_result);
            if (!$result) {
                echo "Error checking result table: " . mysqli_error($connect);
                break;
            }

            if ($result->num_rows == 0) {
                // Delete candidate images associated with the election
                $queryImage = "SELECT Image FROM candidate WHERE Position = '$electionTitle'";
                $resultImage = mysqli_query($connect, $queryImage);
                if (!$resultImage) {
                    echo "Error fetching candidate images: " . mysqli_error($connect);
                    break;
                }

                if (mysqli_num_rows($resultImage) > 0) {
                    while ($row = mysqli_fetch_assoc($resultImage)) {
                        $imgFileName = $row['Image'];
                        $image_path = "../uploads/Candidate-Image/" . $imgFileName;
                        if (file_exists($image_path)) {
                            if (!unlink($image_path)) {
                                echo "Error deleting candidate image: Unable to delete $image_path";
                                // continue; // Optionally, you can choose to continue to the next iteration
                            }
                        }
                    }
                }
            }

            // Delete the election record
            $election_delete_query = "DELETE FROM election WHERE Id = $user_id";
            if (!mysqli_query($connect, $election_delete_query)) {
                echo "Error deleting election record: " . mysqli_error($connect);
                break;
            }

            // Delete candidates and votes associated with the election
            $delete_candidates_query = "DELETE FROM candidate WHERE Position = '$electionTitle'";
            $delete_votes_query = "DELETE FROM votes WHERE ElectionId = '$user_id'";
            if (!mysqli_query($connect, $delete_candidates_query) || !mysqli_query($connect, $delete_votes_query)) {
                echo "Error deleting candidates or votes: " . mysqli_error($connect);
                break;
            }

            // All operations successful
            // echo "Deletion successful";

            // Redirect to the position page
            header("Location: ../Routes/position.php");
            exit;
            break;


        default:
            // Default redirect if originating_page is not recognized
            header("Location: ../Routes/index.html");
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