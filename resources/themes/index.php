<?php
header('Content-Type: application/json');
echo json_encode(glob('*.css'));
?>
