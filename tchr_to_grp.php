<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceil"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teachers from `enseignement`
$sql_teachers = "SELECT num_ens, nom_ens, pre_ens FROM enseignement";
$result_teachers = $conn->query($sql_teachers);

// Fetch groups from `groupe`
$sql_groups = "SELECT num_group, nom_group FROM groupe";
$result_groups = $conn->query($sql_groups);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_ens = $_POST["num_ens"];  // Selected teacher
    $num_groups = isset($_POST["num_groups"]) ? $_POST["num_groups"] : []; // Selected groups (array)

    if (!empty($num_ens) && !empty($num_groups)) {
        // Assign multiple groups to the teacher
        foreach ($num_groups as $num_group) {
            $update_sql = "UPDATE groupe SET num_ens = ? WHERE num_group = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $num_ens, $num_group);
            $stmt->execute();
        }
        $message = "Groups assigned successfully!";
        $message_type = "success";
    } else {
        $message = "Please select a teacher and at least one group.";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Groups to Teacher</title>
    <link rel="stylesheet" href="css/tchr_to_grp.css"> <!-- Link to CSS file -->
</head>
<body>
<?php include("header.php") ?>
    <div class="container">
        <h1 class="h">Assign Groups to Teacher</h2>

        <!-- Success or Error Message -->
        <?php if (isset($message)) { ?>
            <div class="message <?= $message_type ?>">
                <?= $message ?>
            </div>
        <?php } ?>

        <form method="POST" class="assign-groups-form">
            <label for="num_ens">Select Teacher:</label>
            <select name="num_ens" id="num_ens" required>
                <option value="">-- Select a Teacher --</option>
                <?php while ($row = $result_teachers->fetch_assoc()) { ?>
                    <option value="<?= $row['num_ens'] ?>"><?= $row['nom_ens'] . " " . $row['pre_ens'] ?></option>
                <?php } ?>
            </select>

            <label for="num_groups">Select Groups:</label>
            <select name="num_groups[]" id="num_groups" required>
                <?php while ($row = $result_groups->fetch_assoc()) { ?>
                    <option value="<?= $row['num_group'] ?>"><?= $row['nom_group'] ?></option>
                <?php } ?>
            </select>

            <button type="submit">Assign Groups</button>
        </form>
    </div>
    <?php include("footer.php") ?>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>