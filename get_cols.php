<?php
$conn = new mysqli("localhost", "root", "", "cosmetics_db");
$res = $conn->query("SHOW COLUMNS FROM users");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
