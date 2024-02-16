<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
  header('location: loginPage.html');
  exit;
}

include("../api/connect.php");
$userdata = $_SESSION['userdata'];

// Fetch user data from the database
$query = "SELECT * FROM candidate";
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
</head>

<body>
    <div class="container">
        <?php include './components/_header.php' ?>

        <nav>
            <div class="main">
                <h3>Candidates </h3>
                <hr>
                <div class="info">

                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<div class='card'>";
                      echo "<div class='userImage'><img class='user-image' src='../uploads/{$row['Image']}' alt='Image'></div>";
                      echo "<span class='label'>Name : <strong>{$row['Full_Name']}</strong></span > ";
                      echo "<span class='label'>Position : <strong>{$row['Position']}</strong></span > ";
                      echo "<span class='label'>Description : <strong>{$row['Description']}</strong>  </span >";
                      echo "
              <form action='../api/process_action.php' method='post'>
                <input type='hidden' name='user_id' value='{$row['Id']}'>
                <input type='hidden' name='originating_page' value='candidate'>
                <button type='submit' name='reject' class='reject'>Remove</button>
               </form>
              ";
                      echo "</div>";
                    }
                    ?>
                </div>

                <button class="more btn_more">Add Candidate</button>


            </div>
        </nav>
    </div>

    <!-- Pop Up Box Form -->
    <div class="addMore pop_box" id="modal">
        <h2>Add Candidate</h2>
        <hr>
        <form action="../api/candidateForm.php" method="post" id="addC" enctype="multipart/form-data">
            <label for="name">Candidate Name:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="image">Image(JPG, JPEG, PNG)</label>
            <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" alt="Candidate Image" required>
            <br>
            <label for="pos">Position:</label>
            <select name="pos" id="pos" required>
                <option value="" selected disabled>-- Choose a position --</option>
                <?php
                // Fetch data from the 'election' table 
                $sql = "SELECT Title,Status FROM election";
                $result = $connect->query($sql);

                // Populate the <select> element with options 
                while ($row = $result->fetch_assoc()) {
                  if ($row['Status'] == 'Ongoing') {
                    echo "<option value='" . $row['Title'] . "'>" . $row['Title'] . "</option>";
                  }
                }
                ?>
            </select>
            <label for="description">Description :-</label>
            <textarea name="description" id="description" rows="6" cols="40" required></textarea>
            <br>
            <div class="btns">
                <button type="submit" id="btnC">Add Candidate</button>
                <button type="reset" class="cancel btn_cancel">cancel</button>
            </div>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>

</html>