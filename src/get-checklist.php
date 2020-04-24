<?php

session_start();
include('functions.php');

$checklists = getProjectChecklists($_GET['projectID'])->fetchAll(PDO::FETCH_ASSOC);
$response = json_encode($checklists);
echo $response;  

?>