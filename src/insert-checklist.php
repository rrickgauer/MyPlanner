<?php

session_start();
include('functions.php');


insertProjectChecklist($_POST['projectID'], $_POST['name']);
$checklists = getProjectChecklists($_POST['projectID'])->fetchAll(PDO::FETCH_ASSOC);

$response = json_encode($checklists);
echo $response;                                                   



































?>