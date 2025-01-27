<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admission";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID from URL
$id = $_GET['id'];

// Delete the record
$sql = "DELETE FROM enquiry WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: admin_dashboard.php");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
