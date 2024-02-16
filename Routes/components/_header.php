<?php
echo "
<header>
      <h2 id='title'>Online Voting System</h2>
      <ul>
      <li><a href='adminPannel.php' class='btn'>DashBoard</a></li>

      <li><a href='position.php' class='btn'>Position</a></li>
      <li><a href='candidate.php' class='btn'>Candidate</a></li>
      <li><a href='voter.php' class='btn'>Voters</a></li>
      <li>
        <a href='pendingVoter.php' class='btn'>PendingVoters</a>
      </li>
      <div class='welcome'>
      <h3> <span id='user'>
          Admin
        </span></h3>
      <a href='../api/logout.php' id='logout'>LogOut</a>
    </div>
    </ul>
     
    </header>
";
?>