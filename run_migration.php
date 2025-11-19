<?php
// Load configuration
$config = require 'config.php';
$dbConfig = $config['db'];

// Create connection
// Use mysqli for better error handling and multi-query support
$conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "Database connection successful.\n";

// Read the SQL file
$sql = file_get_contents('sql/schema.sql');
if ($sql === false) {
    die("Error reading schema.sql file.");
}

echo "Read schema.sql file successfully.\n";

// Execute multi query
if ($conn->multi_query($sql)) {
    // It's important to consume all results from the multi_query
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Migration script executed successfully.\n";

} else {
  echo "Error executing migration script: " . $conn->error;
}

$conn->close();
?>
