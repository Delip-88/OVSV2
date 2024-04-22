<nav>
    <ul>
        <li><a href="personalInfo.php"><i class="fa-solid fa-id-card"></i>Personal Info</a></li>
        <li><a href="election.php"><i class="fa-solid fa-check-to-slot"></i>Elections</a></li>
        <li><a href="results.php"><i class="fa-solid fa-chart-column"></i>Results</a></li>
    </ul>
    <div class='welcome'>
        <h3><span id='user'><?php echo $userdata['Full_Name'] ?></span></h3>
        <a href='../../api/logout.php' id='logout'>LogOut</a>
    </div>
</nav>