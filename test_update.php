<?php
require 'config/Database.php';
require 'models/AdminModel.php';
$db = (new Database())->getConnection();
$m = new AdminModel($db);
$res = $m->updateProduct(62, 'Chì kẻ mày hai đầu M.A.C', 450000, 8, 1, 'assets/uploads/products/4a39fc67abc64c1c44d5edbcdceefe24_20260708_012015.jpg', 19, 57, 0, 0, 1);
var_dump($res);
if (!$res) {
    echo $db->error;
}
?>
