<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admission";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['num'];
    $course = implode(", ", $_POST['course']);  // Assuming course is passed as an array
    $message = $_POST['message'];

    // Insert the data into the enquiry table
    $sql = "INSERT INTO enquiry (name, email, phone, courses, message) VALUES ('$name', '$email', '$phone', '$course', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Enquiry submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
