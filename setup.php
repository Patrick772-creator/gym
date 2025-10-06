<?php
include 'config/db.php';

$sql = file_get_contents('schema.sql');

if ($conn->multi_query($sql)) {
    echo "Tables created successfully.<br>";
} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}

$conn->close();
?>
