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
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'components/_sidebar.php' ?>


    <div class="container">
        <?php include 'components/_header.php' ?>
        <nav>
            <div class="main">
                <div class="main-container">
                    <div class="comments">
                        <h2>Feedbacks</h2>
                        <hr>
                        <div class="comments-cover">
                            <?php
                            $query = "SELECT * FROM feedback";
                            if ($result = mysqli_query($connect, $query)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $userId = $row['User_Id'];

                                    $imageQuery = "SELECT Image FROM users WHERE Id = ?";
                                    if ($stmt = mysqli_prepare($connect, $imageQuery)) {
                                        mysqli_stmt_bind_param($stmt, "i", $userId);
                                        mysqli_stmt_execute($stmt);
                                        $imageResult = mysqli_stmt_get_result($stmt);
                                        $resultImage = mysqli_fetch_assoc($imageResult);

                                        $userImage = $resultImage ? $resultImage['Image'] : 'def.jpg';

                                        echo "<div class='comments_box'>";
                                        echo "<div class='info_cover'>";
                                        echo "<div class='user_image_container'><img src='../uploads/{$userImage}' onerror=\"this.src='../img/def.jpg'\"></div>";
                                        echo "<strong class='user_name'> {$row['User_Name']}</strong>";
                                        echo "</div>";
                                        echo "<h4 class='gray'>Election : {$row['Election_Title']}</h4>";
                                        echo "<p class='user_feedback'>{$row['Feedback']}</p>";
                                        echo "<small class='timestamp'>{$row['Time_Stamp']}</small>";
                                        echo "<form action='../api/process_action.php' method='post' class='feedback_form'>";
                                        echo "<input type='hidden' name = 'user_id' value = '{$row['Id']}'>";
                                        echo "<input type='hidden' name='Title' value='0'>";
                                        echo "<input type='hidden' name='eId' value='0'>";
                                        echo "<input type='hidden' name='originating_page' value='feedback'>";
                                        echo "<button type='submit' name='reject' value='Delete' class='feedback_delete'onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                                        echo "</form>";
                                        echo "</div>";

                                        mysqli_stmt_close($stmt);
                                    } else {
                                        echo "Failed to prepare image query: " . mysqli_error($connect);
                                    }
                                }
                            } else {
                                echo "Connection failed: " . mysqli_error($connect);
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <?php include "components/_footer_admin.php" ?>
    <script src="js/sidebar.js"></script>
</body>

</html>