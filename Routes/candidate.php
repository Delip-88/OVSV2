<?php
session_start();
if ($_SESSION['userdata']['Role'] !== 'admin') {
  header('location: index.html');
  exit;
}

include ("../api/connect.php");
$userdata = $_SESSION['userdata'];

// Fetch user data from the database
$query = "SELECT * FROM candidate";
$result = mysqli_query($connect, $query);

// Check if the query was successful
if (!$result) {
  die ("Query failed: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SecureVote - Online Voting Platform</title>
    <link rel="stylesheet" href="../css/Home.css" />
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
        <script src="js/jquery-3.7.1.min.js"></script>

</head>

<body>
    <?php include 'components/_sidebar.php' ?>

    <div class="container">
        <?php include './components/_header.php' ?>

        <nav>
            <div class="main">
                <div class="addVoter">
                    <h3>Candidates </h3>
                    <input type="text" name="searchInput" id="searchInput2" placeholder="Search by Position">
                    <button class="more btn_more">Add Candidate</button>
                </div>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Image</th>
                                <th>Full Name</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userData">
                            <?php
                    $sn= 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>{$sn}</td>";
                      $sn++;
                      echo "<td><img class='user-image' src='../uploads/Candidate-Image/{$row['Image']}' alt='Image' onerror=\"this.src='../img/def.jpg'\"></td>";
                      echo " <td>{$row['Full_Name']}</td> ";
                      echo " <td>{$row['Position']}</td> ";
                      echo " <td>{$row['Description']}</td>";
                      echo "<td>
              <form action='../api/process_action.php' method='post'>
                <input type='hidden' name='user_id' value='{$row['Id']}'>
                <input type='hidden' name='originating_page' value='candidate'>
                <button type='submit' name='reject' class='reject'>Remove</button>
               </form>
               </td>
              ";
                    }
                    ?>
                        </tbody>
                    </table>
                </div>
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
                  if ($row['Status'] == 'Ongoing' || $row['Status']== 'Inactive') {
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
    <?php include "components/_footer_admin.php" ?>

    <script src="js/script.js"></script>
    <script src="js/sidebar.js"></script>
    <script src="js/search.js"></script>

</body>

</html>