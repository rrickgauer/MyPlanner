<?php

session_start();
include('functions.php');


if (isset($_GET['projectID']))
  deleteProject($_GET['projectID']);

header("Location: index.php");
exit;

?>