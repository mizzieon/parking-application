<?php
session_start();
require 'dbh.inc.php';
require "functions.php";

if (isset($_POST['passwordSubmit'])) {
	$username = $_SESSION['usernameToRegister'];
	$password = $_POST['inputPassword'];
	$repeatPassword = $_POST['repeatPassword'];

	if (empty($username) || empty($password) || empty($repeatPassword)) {
		header("Location: ../createpassword.php?error=emptyfields");
		exit();
	}
	elseif ($password != $repeatPassword) {
		header("Location: ../createpassword.php?error=passwordsdonotmatch");
		exit();
	}
	else {
		$sql = "UPDATE users SET password = ? WHERE username = ?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../createpassword.php?error=sqlerror");
			exit();
		}
		else{
			$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
			mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $username);
			mysqli_stmt_execute($stmt);
			header("Location: ../index.php?info=success");
			exit();
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}
else{
	header("Location: ../index.php");
	exit();
}