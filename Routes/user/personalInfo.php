<?php
$timeout = 30 * 24 * 60 * 60;
session_set_cookie_params($timeout);
session_start();
if ($_SESSION['userdata']['Role'] !== 'user') {
    header('location: ../../Routes/index.html');
    exit;
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
    <div class="mainContainer">

    <header>
        <h1>Online Voting System</h1>
<?php include '../components/_nav.php' ?>
    </header>
    <main>
        <div class="profile">
            <i class="fa-solid fa-gear setting"></i>

            <figure class="userImage">
                <img src="../../uploads/<?php echo $userdata['Image']; ?>" alt="userimage" onerror="this.src='../../img/def.jpg'">
            </figure>
        </div>
        <div class="info">
            <span>Name <br>
                <div class="data" id="Full_Name"><?php echo $userdata['Full_Name']; ?></div>
            </span>
            <span>DOB <br>
                <div class="data"><?php echo $userdata['DOB']; ?></div>
            </span>
            <span>Age <br>
                <div class="data"><?php echo $userdata['Age']; ?></div>
            </span>
            <span>Number <br>
                <div class="data" id="Number"><?php echo $userdata['Number']; ?></div>
            </span>
            <span>Email <br>
                <div class="data"><?php echo $userdata['Email']; ?></div>
            </span>
            <div class="dataCover">

                <span>Address <br>
                    <div class="data" id="Address"><?php echo $userdata['Address']; ?></div>
                </span>
                <span>Verified <br>
                    <div class="data"><?php echo ($userdata['Verified'] === 1) ? 'True' : 'False'; ?>
                    </div>
                </span>
            </div>
        </div>
    </main>
<?php include '../components/_footer.php'; ?>
    <!-- POP-UP FORUM -->
    <div class="pop_box2">
        <h2>Edit</h2>
        <hr>
        <small id='passwordError'></small>
        <form action="../../api/passwordChange.php" method="post" class="form">
            <br>
            <label for="password">Current Pasword:</label>
            <input type="text" name="password" id="password" required>
            <div class="messagejs"></div>
            <br>
            <br>
            <label for="newPassword">New Password:</label>
            <input type="text" name="newPassword" id="newPassword" onkeyup="passwordValid(this.value)" required>
            <div class="messagejs passwordmsg"></div>
            <br>
            <br>
            <label for="rePassword">Confirm Password:</label>
            <input type="text" name="rePassword" id="rePassword" onkeyup="cpasswordValid()" required>
            <div class="messagejs cpasswordmsg"></div>
            <br>
            <br>
            <div class="btns">
                <input type="hidden" name="UID" value="<?php echo $userdata['Id']; ?>">
                <button type="submit" id="btnC">Apply Changes</button>
                <button type="reset" class="cancel btn_cancel2">cancel</button>
            </div>
        </form>

    </div>

    </div>

    <script src='../js/userScript.js'></script>
    <script src="../js/passwordValidation.js"></script>
</body>

</html>