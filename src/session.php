<?php

// start session
// if user id is not set, return to login page
session_start();
if (!isset($_SESSION['userID'])) {
	header('Location: login.php');
	exit;
}
?>