<?php
$serverName="localhost";
$userName="root";
$password="";
$dbname="voting";
$connect=mysqli_connect($serverName,$userName,$password,$dbname);

if ($connect->connect_error) {
    die("connection failed" . $connect->connect_error);
}

?>