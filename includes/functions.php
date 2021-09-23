<?php
require 'dbh.inc.php';

// ******************** FUNCTION DEFINITIONS
/*This function checks whether the password is set for a given username.
if the password is not set, the user is sent to the password creation page.*/
	function IsPasswordSet($username, $conn){
		$sql = "SELECT * FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../index.php?error=sqlerror");
			exit();
		}else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			//Get the password and see if it is null
			if ($row = mysqli_fetch_assoc($result)) {
				if ($row['password'] == null) {
					return false;
				}else{
					return true;
				}
			}
			else {
				header("Location: ../index.php?error=sqlerror");
				exit();
			}
		}
	}

	/*This function checks if the username entered is valid*/
	function UsernameValid($username, $conn)
	{
		$sql = "SELECT * FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../index.php?error=sqlerror");
			exit();
		}else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);

			if ($resultCheck > 0) {
				return true;
			}else{
				return false;
			}
		}
	}

/*This function displays the appropriate error message, depending on which error
the user name*/
function DisplayErrorMessage($error)
{
	if ($error == "emptyUsername") {
		echo '<div class="alert alert-danger" role="alert">
		Username cannot be empty.
		</div>';
	}

	if ($error == "invalidUsername") {
		echo '<div class="alert alert-danger" role="alert">
		Invalid username or password.
		</div>';
	}

	if ($error == "sqlerror") {
		echo '<div class="alert alert-danger" role="alert">
		SQL Error!
		</div>';
	}
	if ($error == "emptyfields") {
		echo '<div class="alert alert-danger" role="alert">
		All fields must be filled out.
		</div>';
	}
	if ($error == "passwordsdonotmatch") {
		echo '<div class="alert alert-danger" role="alert">
		Both passwords must match.
		</div>';
	}
	if ($error == "badpassword") {
		echo '<div class="alert alert-danger" role="alert">
		Incorrect password.
		</div>';
	}
	if ($error == "databaseerror") {
		echo '<div class="alert alert-danger" role="alert">
		A database error has occured.
		</div>';
	}
	if ($error == "logouterror") {
		echo '<div class="alert alert-danger" role="alert">
		There was an error logging out.
		</div>';
	}
}

/*This function displays basic non-error messages*/
function DisplayInfoMessage($info)
{
	if ($info == "success") {
		echo '<div class="alert alert-info" role="alert">
		You are now registered. Please sign in.
		</div>';
	}
	if ($info == "logout") {
		echo '<div class="alert alert-info" role="alert">
		You have now successfully logged out.
		</div>';
	}
}