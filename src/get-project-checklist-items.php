<?php

session_start();
include('functions.php');

$checklistID = $_GET['checklistID'];



$items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($items);
exit;

?>