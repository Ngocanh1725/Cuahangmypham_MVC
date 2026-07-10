<?php
$db = new mysqli('localhost', 'root', '', 'cosmetics_db');
$r = $db->query("SELECT * FROM products WHERE id=62");
print_r($r->fetch_assoc());
$r = $db->query("SELECT id, name FROM products WHERE is_summer=1");
while($row = $r->fetch_assoc()) {
    print_r($row);
}
?>
