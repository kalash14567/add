<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admission";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Fetch existing data
$sql = "SELECT * FROM enquiry WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $courses = implode(", ", $_POST['courses']);
    $message = $_POST['message'];

    // Update record
    $update_sql = "UPDATE enquiry SET name='$name', email='$email', phone='$phone', courses='$courses', message='$message' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enquiry</title>
</head>
<body>
    <h1>Edit Enquiry</h1>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br><br>
        
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required><br><br>
        
        <label>Courses:</label>
        <input type="text" name="courses" value="<?php echo $row['courses']; ?>" required><br><br>
        
        <label>Message:</label>
        <textarea name="message" required><?php echo $row['message']; ?></textarea><br><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>
<?php
$conn->close();
?>
