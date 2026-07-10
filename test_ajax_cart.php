<?php
session_start();
$_SESSION['cart'] = [1 => 1]; // Dummy item
$_GET['controller'] = 'cart';
$_GET['action'] = 'ajaxGetCart';

ob_start();
require 'index.php';
$output = ob_get_clean();

echo "OUTPUT:\n";
var_dump($output);
?>
