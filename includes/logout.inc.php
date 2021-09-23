<?php
require 'dbh.inc.php';

session_start();

if (!isset($_SESSION['username'])) {
	header("Location: ../index.php");
	exit();
}

$username = $_SESSION['username'];

$actionSql = "INSERT INTO actions (user_username, time_stamp, action_type, details) VALUES (?,(SELECT NOW()), 'signed out','User signed out');";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $actionSql)) {
	header("Location: ../index.php?error=logouterror");
	exit();
}
else{
	mysqli_stmt_bind_param($stmt, "s",$username);
	mysqli_stmt_execute($stmt);
}

session_start();
session_unset();
session_destroy();
header("Location: ../index.php?info=logout");