<?php
$conn = new mysqli('localhost', 'root', '', 'cosmetics_db');
$tables = ['users', 'orders', 'chat_messages'];
foreach ($tables as $t) {
    echo "TABLE: $t\n";
    $res = $conn->query("DESCRIBE $t");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo $row['Field'] . ' - ' . $row['Type'] . "\n";
        }
    }
    echo "\n";
}
?>
