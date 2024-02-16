<?php
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();
if ($_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/loginPage.html');
}
$userdata = $_SESSION['userdata'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>
    <link rel="stylesheet" href="../../css/userPannel.css">
    <script src="https://kit.fontawesome.com/192f9dadc6.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <h1>Online Voting System</h1>
        <nav>
            <ul>
                <li><a href="" class='active'>Personal Info</a></li>
                <li><a href="election.php">Elections</a></li>
                <li><a href="">Results</a></li>
            </ul>
            <div class='welcome'>
                <h3><span id='user'><?php echo $userdata['Full_Name'] ?></span></h3>
                <a href='../../api/logout.php' id='logout'>LogOut</a>
            </div>
        </nav>
    </header>
    <main>
        <div class="profile">
            <figure class="userImage">
                <img src="../../uploads/<?php echo $userdata['Image']; ?>" alt="userimage" srcset="">
            </figure>
            <button>Change Profile Picture</button>
            <button class='btn_more'><i class="fa-solid fa-pen"></i>Edit Profile</button>
        </div>
        <div class="info">
            <span>Name <br>
                <div class="data"><?php echo $userdata['Full_Name']; ?></div>
            </span>
            <span>DOB <br>
                <div class="data"><?php echo $userdata['DOB']; ?></div>
            </span>
            <span>Age <br>
                <div class="data"><?php echo $userdata['Age']; ?></div>
            </span>
            <span>Number <br>
                <div class="data"><?php echo $userdata['Number']; ?></div>
            </span>
            <span>Email <br>
                <div class="data"><?php echo $userdata['Email']; ?></div>
            </span>
            <span>Address <br>
                <div class="data"><?php echo $userdata['Address']; ?></div>
            </span>
            <span>Verified <br>
                <div class="data"> True</div>
            </span>
        </div>
    </main>
    <!-- POP-UP FORUM -->
    <div class="pop_box" id="modal">
        <h2>Edit</h2>
        <small id='error'></small>

        <hr>
        <form action="../../api/updateDetails.php" method="post" class="pos" id="adC">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value='<?php echo $userdata['Full_Name'] ?>'>
            <br>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value='<?php echo $userdata['Address'] ?>'>
            <br>
            <label for="number">Number:</label>
            <input type="text" name="number" id="number" value='<?php echo $userdata['Number'] ?>'>
            <br>
            <div class="btns">
                <input type="hidden" name="uid" value="<?php echo $userdata['Id'] ?>">
                <button type="submit" id="btnC">Apply Changes</button>
                <button type="reset" class="cancel btn_cancel">cancel</button>
            </div>
        </form>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>