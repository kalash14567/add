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
    $user_name = $_POST['name'];
    $user_pass = $_POST['pass'];

    // Check if the user is an admin (You can modify this as needed)
    if ($user_name === 'admin' && $user_pass === '1234') {
        // Redirect to the admin dashboard if it's an admin login
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check if the user is a student
    $sql = "SELECT * FROM student WHERE username='$user_name' AND password='$user_pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Redirect to the enquiry form if login is successful
        header("Location: enquiryform.html");
        exit();
    } else {
        echo "Invalid username or password!";
    }
}

$conn->close();
?>
