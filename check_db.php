<?php
$conn = new mysqli('localhost', 'root', '', 'cosmetics_db');
$tables = ['stores', 'products', 'inventory_logs'];
foreach ($tables as $t) {
    echo "TABLE: $t\n";
    $res = $conn->query("DESCRIBE $t");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
    } else {
        echo "Not found.\n";
    }
    echo "\n";
}
$res = $conn->query("SHOW TABLES LIKE 'store_inventory'");
if ($res && $res->num_rows > 0) {
    echo "TABLE: store_inventory EXISTS\n";
} else {
    echo "TABLE: store_inventory DOES NOT EXIST\n";
}
?>
