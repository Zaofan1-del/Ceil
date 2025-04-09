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
if (isset($_GET['delete_num_prof'])) {
    $delete_num_prof = $conn->real_escape_string($_GET['delete_num_prof']);
    $delete_sql = "DELETE FROM enseignement WHERE num_ens = '$delete_num_prof'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<p>Teacher deleted successfully.</p>";
    } else {
        echo "<p>Error occurred during deletion: " . $conn->error . "</p>";
    }
}

// Fetch teacher list with search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT num_ens, nom_ens, pre_ens FROM enseignement WHERE nom_ens LIKE '%$search%' OR pre_ens LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher List</title>
    <link rel="stylesheet" href="css/list_teachers.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

    <div class="container">
        <h1 class="h">Teacher List</h1>
        
        <!-- Search Form -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for a teacher" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search" class="button">
        </form>

        <table >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['num_ens']) ?></td>
                            <td><?= htmlspecialchars($row['nom_ens']) ?></td>
                            <td><?= htmlspecialchars($row['pre_ens']) ?></td>
                            <td>
                                <a href="list_teachers.php?delete_num_prof=<?= htmlspecialchars($row['num_ens']) ?>" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                    <button>Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No teachers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
$conn->close();
?>