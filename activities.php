<?php
include('db.php');

// Gérer l'ajout d'une nouvelle activité
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_activity'])) {
        $libAct = $_POST['libAct'];

        // Validation des données (vous devrez peut-être ajouter des vérifications supplémentaires)
        if (!empty($libAct)) {
            // Ajouter l'activité à la base de données
            $query = "INSERT INTO activity  VALUES ('','$libAct')";
            $result = $conn->query($query);

            if ($result) {
                echo '<div class="alert alert-success" role="alert">Activity added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error adding activity: ' . $conn->error . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Please enter activity name!</div>';
        }
    }
}

// Récupérer la liste des activités depuis la base de données
$query = "SELECT * FROM activity";
$result = $conn->query($query);

// Gérer la suppression d'une activité
if (isset($_GET['delete'])) {
    $deleteCodeAct = $_GET['delete'];

    // Supprimer l'activité de la base de données
    $query = "DELETE FROM activity WHERE CodeAct = '$deleteCodeAct'";
    $result = $conn->query($query);

    if ($result) {
        echo '<div class="alert alert-success" role="alert">Activity deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting activity: ' . $conn->error . '</div>';
    }
}

// Récupérer la liste des activités depuis la base de données
$query = "SELECT * FROM activity";
$result = $conn->query($query);

// Gérer la récupération des données pour la mise à jour
if (isset($_GET['edit'])) {
    $editCodeAct = $_GET['edit'];

    // Récupérer les données de l'activité à éditer
    $editQuery = "SELECT * FROM activity WHERE CodeAct = '$editCodeAct'";
    $editResult = $conn->query($editQuery);

    if ($editResult->num_rows > 0) {
        $editRow = $editResult->fetch_assoc();
        $editLibAct = $editRow['LibAct'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Manage Activities</title>

</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="container mt-5">
        <h2>Manage Activities</h2>

        <!-- Form to add/update a new activity -->
        <form method="post" action="">
            <div class="form-group">
                <label for="libAct">Activity Name:</label>
                <input type="text" class="form-control" id="libAct" name="libAct" value="<?php echo isset($editLibAct) ? $editLibAct : ''; ?>" required>
            </div>
            <?php if (isset($editCodeAct)): ?>
                <input type="hidden" name="codeAct" value="<?php echo $editCodeAct; ?>">
                <button type="submit" class="btn btn-primary" name="update_activity">Update Activity</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="add_activity">Add Activity</button>
            <?php endif; ?>
        </form>

        <!-- Display the list of activities -->
        <?php
        if ($result->num_rows > 0) {
            echo '<ul class="activity-list">';
            while ($row = $result->fetch_assoc()) {
                echo '<li class="activity-item">' . $row['LibAct'] . ' 
                        <div class="activity-actions">
                            <a href="activities.php?edit=' . $row['CodeAct'] . '" class="btn btn-warning btn-sm btn-action">Edit</a>
                            <a href="activities.php?delete=' . $row['CodeAct'] . '" class="btn btn-danger btn-sm btn-action" onclick="return confirm(\'Are you sure?\')">Delete</a>
                        </div>
                      </li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No activities found.</p>';
        }
        ?>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
