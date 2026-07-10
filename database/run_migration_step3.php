<?php
require 'config/Database.php';
$db = (new Database())->getConnection();

$sql = file_get_contents('database/migration_step3_inventory.sql');

// Execute multi query
if ($db->multi_query($sql)) {
    do {
        if ($result = $db->store_result()) {
            while ($row = $result->fetch_row()) {
                printf("%s\n", $row[0]);
            }
            $result->free();
        }
    } while ($db->more_results() && $db->next_result());
    echo "Migration completed successfully!\n";
} else {
    echo "Error executing migration: " . $db->error . "\n";
}
$db->close();
?>
