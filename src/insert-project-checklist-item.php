<?php

session_start();
include('functions.php');

$checklistID = $_POST['checklistID'];
$content = $_POST['content'];

insertProjectChecklistItem($checklistID, $content);


// retrieve the updated list

$items = getProjectChecklistItems($checklistID)->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($items);


?>