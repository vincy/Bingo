<?php
error_reporting(0);
require 'Bingo.php';
$b = new Bingo((int)$_GET['cards_number']);
print json_encode($b->get());
//$b->createCartella();
?>