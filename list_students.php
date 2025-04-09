<?php
// Database connection information
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete_num_etud'])) {
    $delete_num_etud = $conn->real_escape_string($_GET['delete_num_etud']);
    $delete_sql = "DELETE FROM etudiant WHERE num_etud = '$delete_num_etud'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<p>Student deleted successfully.</p>";
    } else {
        echo "<p>Error occurred during deletion: " . $conn->error . "</p>";
    }
}

// Fetch student list with search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM etudiant WHERE nom_etud LIKE '%$search%' OR prenom_etud LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="css/list_students.css"> 
</head>
<body>
<?php include("header.php") ?>

    <div class="container">
        <h1 class="h">Student List</h1>
        
        <!-- Search Form -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for a student" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search" class="button">
        </form>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $student_id = $row['num_etud'];
                        $student_name = $row['nom_etud'] . ' ' . $row['prenom_etud'];

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['num_etud']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nom_etud']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prenom_etud']) . "</td>";
                        echo "<td>
                                <a href='list_students.php?delete_num_etud=" . htmlspecialchars($row['num_etud']) . "' onclick=\"return confirm('Are you sure you want to delete this student?')\">
                                    <button>Delete</button>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No results found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
$conn->close();
?>