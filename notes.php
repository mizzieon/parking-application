<?php
/*session vaiables*/
$create_data_ready	//used as a switch to indicate that there is readable data to be read from the session
$create_plate	//will store the plate information that will be used by either edit.php or view.php

$edit_data_ready
$edit_plate

$permit_id_numbers //this will store an array of permit id's to be saved
$violation_id_numbers

/*These are the session variables used for user management*/
$username
$access

/*These are the session variables to be used during the user registration process*/
$usernameToRegister

/*Different Levels of users
3 view search create edit violations
2 view search create edit
1 view search
*/

/*test accounts used for logging in
dwilson password level3 
testting1 password level3
testing2 password level3*/

?>