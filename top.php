<?php

$serial_key = $_GET['serial_key'];
$url = "https://www.sorizo.net/index.php?serial_key=" . $serial_key;

header("Location:".$url);
exit;

?>
