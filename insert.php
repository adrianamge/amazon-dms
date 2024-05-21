<?php
$servername = "localhost";
$username = "mysql";
$password = "password";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insert_values($conn, $start_id, $values) {
    // Check the most recent id
    $sql = "SELECT MAX(id) AS max_id FROM records";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $max_id = $row['max_id'];
        if ($max_id !== null) {
            $start_id = $max_id + 1;
        }
    }

    // Insert new values
    $stmt = $conn->prepare("INSERT INTO records (id, value) VALUES (?, ?)");
    foreach ($values as $i => $value) {
        $current_id = $start_id + $i;
        $stmt->bind_param("is", $current_id, $value);
        $stmt->execute();
    }

    $stmt->close();
}

// Values to insert
$values_to_insert = ['value1', 'value2', 'value3'];

// Start inserting from id 1 (or continue from the most recent id)
insert_values($conn, 1, $values_to_insert);

$conn->close();
?>
