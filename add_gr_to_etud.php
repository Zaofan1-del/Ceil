<?php
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

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['group'])) {
        foreach ($_POST['group'] as $student_id => $group_code) {
            // Update the group
            if ($group_code === "") {
                $update_sql = "UPDATE etudiant SET num_group = NULL, num_niv = NULL WHERE num_etud = ?";
            } else {
                // Get the level from the group
                $level_sql = "SELECT num_niv FROM groupe WHERE num_group = ?";
                $level_stmt = $conn->prepare($level_sql);
                $level_stmt->bind_param("s", $group_code);
                $level_stmt->execute();
                $level_stmt->bind_result($num_niv);
                $level_stmt->fetch();
                $level_stmt->close();

                // Update the student with the new group and level
                $update_sql = "UPDATE etudiant SET num_group = ?, num_niv = ? WHERE num_etud = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("ssi", $group_code, $num_niv, $student_id);
            }

            if ($stmt->execute()) {
                $message = "Update successful!";
            } else {
                $message = "Update failed!";
            }
        }
    }
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM etudiant WHERE nom_etud LIKE '%$search%' OR prenom_etud LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="css/grp_to_etud.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>

<div class="container">
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h1 class="h">Student Management</h1>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search for a student" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Search" class="button">
    </form>

    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Group Selection</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $student_id = $row['num_etud'];
                        $student_name = $row['nom_etud'] . ' ' . $row['prenom_etud'];

                        $group_code = $row['num_group'];
                        $group_sql = "SELECT g.nom_group, n.nom_niv FROM groupe g JOIN niveau n ON g.num_niv = n.num_niv WHERE g.num_group = '$group_code'";
                        $group_result = $conn->query($group_sql);
                        $level_name = '';
                        if ($group_result->num_rows > 0) {
                            $group_row = $group_result->fetch_assoc();
                            $level_name = $group_row['nom_niv'];
                        }

                        echo "<tr>";
                        echo "<td>" . $row['num_etud'] . "</td>";
                        echo "<td>" . $student_name . "</td>";

                        echo "<td>";
                        echo "<select name='group[$student_id]'>";
                        echo "<option value=''" . (empty($group_code) ? " selected" : "") . ">No Group</option>";
                        $group_sql = "SELECT * FROM groupe";
                        $group_result = $conn->query($group_sql);
                        while ($group = $group_result->fetch_assoc()) {
                            $selected = ($group['num_group'] == $group_code) ? 'selected' : '';
                            echo "<option value='" . $group['num_group'] . "' $selected>" . $group['nom_group'] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        
                        echo "<td>" . ($group_code ? $level_name : "Not Assigned") . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No results found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <input type="submit" value="Update" class="button">
    </form>
</div>
<?php include("footer.php") ?>

</body>
</html>

<?php
$conn->close();
?>