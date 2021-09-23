<?php
// closing php tag not need because there is no html

$servername = "localhost";
$dBUsername = "derrianwilso2017";
$dBPassword = "Leanderowlpr4";
$dBName = "derrianwilso2017";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}