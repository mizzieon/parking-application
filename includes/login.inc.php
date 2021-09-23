<?php
session_start();
require 'dbh.inc.php';
require "functions.php";

if (!isset($_POST['inputSubmit'])) {
	header("Location: ../index.php");
	/*TODO: clear the usernameToRegister session variable*/
	exit();


}else{
	// Retreive the form data from POST
	$username = $_POST["inputUsername"];
	$password = $_POST["inputPassword"];

	//check if username is empty
	if (empty($username)) {
		header("Location: ../index.php?error=emptyUsername");
		exit();
	}

	//check if the username belongs to a valid user
	if (!UsernameValid($username, $conn)) {
		header("Location: ../index.php?error=invalidUsername");
		exit();
	}else{
		//check if password is set. if not, the user will be sent to the password creation page
		if (!IsPasswordSet($username, $conn)) {
			// send to the password creation page
			$_SESSION['usernameToRegister'] = $username;
			header("Location: ../createpassword.php");
			exit();
		}else{
			//Verify password and send the user to the application
			$sql = "SELECT * FROM users WHERE username = ?;";
			$stmt = mysqli_stmt_init($conn);
			//The mysqli_stmt_prepare checks if the sql code is valid and does not contain any errors
			if (!mysqli_stmt_prepare($stmt,$sql)) {
				header("Location: ../index.php?error=sqlerror");
				exit();
			}
			else {

				mysqli_stmt_bind_param($stmt, "s", $username);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);

				//see if we get a match from the database
				if ($row = mysqli_fetch_assoc($result)) {

					//The following function will take the password the user passed in, hash it, and compare it against the already-hashed password in the database.
					$passwordCheck = password_verify($password, $row['password']);

					//check wether $pwdCheck is true or false
					if ($passwordCheck == false) {
						header("Location: ../index.php?error=badpassword");
						exit();
					}
					elseif ($passwordCheck == true) {

					// create a session of the user to sign them in. also add an event to the activity log.
						$_SESSION['username'] = $row['username'];
						$_SESSION['access'] = $row['access_level'];

						$actionSql = "INSERT INTO actions (user_username, time_stamp, action_type, details) VALUES (?,(SELECT NOW()), 'signed in','User signed in');";
						$stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $actionSql)) {
							header("Location: ../index.php?error=sqlerror");
							exit();
						}
						else{
							mysqli_stmt_bind_param($stmt,"s",$username);
							mysqli_stmt_execute($stmt);
							header("Location: ../application/index.php");
							exit();
						}

					}
					else {

						header("Location: ../index.php?error=badpassword&test=1&check=$passwordCheck");
						exit();
					}
				}
				else {

					header("Location: ../index.php?error=databaserror");
					exit();
				}
			}
		}
	}
}