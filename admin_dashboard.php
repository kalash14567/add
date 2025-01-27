<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admission";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling the CSV download
if (isset($_GET['export_csv'])) {
    $sql = "SELECT * FROM enquiry";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $filename = "enquiry_data_" . date("Y-m-d_H-i-s") . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, array('Name', 'Email', 'Phone', 'Courses', 'Message'));
        
        // Add data rows
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }
}

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

$sql = "SELECT * FROM enquiry LIMIT $start_from, $limit";
$result = $conn->query($sql);

// Count total rows
$count_result = $conn->query("SELECT COUNT(*) AS total FROM enquiry");
$row_count = $count_result->fetch_assoc();
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Enquiry Details</h1>

        <!-- Search Bar -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by name..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
            <input type="submit" value="Search" />
        </form>

        <!-- Download CSV Button -->
        <a href="?export_csv=true" class="btn btn-primary">Download CSV</a>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Courses</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Search functionality
                if (isset($_GET['search']) && $_GET['search'] != "") {
                    $search = $_GET['search'];
                    $sql = "SELECT * FROM enquiry WHERE name LIKE '%$search%' LIMIT $start_from, $limit";
                    $result = $conn->query($sql);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['courses'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td>
                            <a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>Edit</a>
                            <a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No enquiries found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
            <?php } ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
