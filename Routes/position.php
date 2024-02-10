<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
  header('location:loginPage.html');
  exit;
}

include("../api/connect.php");
$userdata = $_SESSION['userdata'];

// Fetch user data from the database
$query = "SELECT * FROM election";
$result = mysqli_query($connect, $query);

// Check if the query was successful
if (!$result) {
  die("Query failed: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SecureVote - Online Voting Platform</title>
  <link rel="stylesheet" href="../css/Home.css" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <div class="container">
  <?php include 'components/_header.php' ?>

    <nav>
    <?php include 'components/_navbar.php' ?>

      <div class="main">
        <h3>Elections</h3>
        <hr>
        <button class="more btn_more ">Create Election</button>
        <div class="elections">

          <?php

          $electionTitles = array(); // Initialize an array to store election titles
          while ($row = mysqli_fetch_assoc($result)) {
            $electionId = $row['Id'];
            $electionTitle = $row['Title'];
            $electionTitles[] = $electionTitle; // Add each election title to the array
          
            echo "<div class='eCard' data-title='{$row['Title']}'> ";
            echo "<h2>Election Title : {$row['Title']} </h2>";
            echo "<p >Starting Date: <span class='stDate'>{$row['StartDate']} </span></p>";
            echo "<p >Ending Date: <span class='endDate'>{$row['EndDate']} </span></p>";
            echo "<p> Status : <span class='status'></span></p>";
            echo "
            <form action='../api/process_action.php' method='post' >
            <input type='hidden' name='user_id' value='{$row['Id']}'>
            <input type='hidden' name='Title' value='{$row['Title']}'>
            <input type='hidden' name='eId' value='$electionId'>
            <input type='hidden' name='originating_page' value='election'>
            <div class='modifybtns'>
            <button name='edit' class='edit'> Edit </button>
            <button type='submit' name='reject' class='reject delete'>Delete</button>
            </div>
            </form>
            ";
            echo "</div>";
          }
          ?>

        </div>



      </div>

    </nav>
  </div>
  <div class="addMore pop_box" id="modal">
    <h2>Create Election</h2>
    <hr>
    <form action="../api/electionForm.php" method="post" class="pos" id="adC">
      <label for="title">Title</label>
      <input type="text" name="title" id="title" required>
      <br>
      <label for="startDate">Starting Date & Time : </label>
      <input type="datetime-local" name="startDate" id="startDate" min="<?php echo date('Y-m-d\TH:i'); ?>">
      <br>
      <label for="endDate">Closing Date & Time :
        </label>
      <input type="datetime-local" name="endDate" id="endDate" min="<?php echo date('Y-m-d\TH:i'); ?>">
      <br>

      <div class="btns">
        <button type="submit" id="btnC">Create Election</button>
        <button type="reset" class="cancel btn_cancel">cancel</button>
      </div>
    </form>
  </div>


<!-- Output the array as a JSON object in a script tag -->
<script>
    var electionTitles = <?php echo json_encode($electionTitles); ?>;
</script>
  <script src="js/script.js"></script>
  <script src="js/updateStatus.js"></script>
</body>

</html>