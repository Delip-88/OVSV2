<?php
include 'connect.php';

// Parse incoming JSON data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if the data is parsed correctly
if ($data === null) {
    error_log('Failed to decode JSON input.');
    echo json_encode(array("success" => false, "message" => "Invalid JSON input"));
    exit();
}

// Check the request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables and an array to keep track of missing parameters
    $electionId = null;
    $electionTitle = null;
    $electionDate = null;
    $candidates = null;
    $missingParameters = [];

    // Assign data from JSON and check for missing parameters
    if (isset($data["electionId"])) {
        $electionId = $data["electionId"];
    } else {
        $missingParameters[] = "electionId";
    }

    if (isset($data["electionTitle"])) {
        $electionTitle = $data["electionTitle"];
    } else {
        $missingParameters[] = "electionTitle";
    }

    if (isset($data["electionDate"])) {
        $electionDate = $data["electionDate"];
    } else {
        $missingParameters[] = "electionDate";
    }

    if (isset($data["candidates"])) {
        $candidates = $data["candidates"];
    } else {
        $missingParameters[] = "candidates";
    }

    // Log incoming data for debugging
    error_log('Election ID: ' . $electionId);
    error_log('Election Title: ' . $electionTitle);
    error_log('Election Date: ' . $electionDate);
    error_log('Candidates: ' . json_encode($candidates));

    // Check if any parameters are missing
    if (count($missingParameters) > 0) {
        $missingParamMessage = implode(", ", $missingParameters);
        echo json_encode(array("success" => false, "message" => "Missing required parameters: " . $missingParamMessage));
        exit();
    }

    // Prepare statements for inserting data and checking for existing data
    $insertStmt = $connect->prepare("INSERT INTO results (Election_Id, Election_Title, Election_Date, Candidate_Name, Candidate_Votes, Percentage, Candidate_Image)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $checkStmt = $connect->prepare("SELECT COUNT(*) FROM results WHERE Election_Id = ? AND Candidate_Name = ?");

    // Loop through each candidate and process
    foreach ($candidates as $candidate) {
        $candidateName = $candidate["candidateName"];
        $voteCount = $candidate["voteCount"];
        $percentage = $candidate["percentage"];
        $imagePath = $candidate["imagePath"];

        // Check if the candidate already exists in the results table for the given election ID
        $checkStmt->bind_param("is", $electionId, $candidateName);
        $checkStmt->execute();
        $checkStmt->store_result();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();

        // If the candidate does not exist, insert the data
        if ($count == 0) {
            $insertStmt->bind_param("isssids", $electionId, $electionTitle, $electionDate, $candidateName, $voteCount, $percentage, $imagePath);
            $insertStmt->execute();

            // Check for errors
            if ($insertStmt->error) {
                echo json_encode(array("success" => false, "message" => "Error inserting candidate data into database"));
                exit();
            }
        }
    }

    // Call the stored procedure to update the Position column
    $triggerQuery = "CALL update_position()";
    mysqli_query($connect, $triggerQuery);

    // Handle potential errors
    if (mysqli_error($connect)) {
        echo json_encode(array("success" => false, "message" => "Error updating position: " . mysqli_error($connect)));
        exit();
    }

    // Respond with success message
    echo json_encode(array("success" => true, "message" => "Election results stored successfully"));
} else {
    // Handle invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
