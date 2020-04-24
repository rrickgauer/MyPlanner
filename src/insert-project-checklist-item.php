<?php

session_start();
include('functions.php');

$checklistID = $_POST['checklistID'];
$content = $_POST['content'];

insertProjectChecklistItem($checklistID, $content);

echo getProjectChecklistItemsCount($checklistID);
exit;



?>