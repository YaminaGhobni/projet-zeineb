<?php
include('db.php');

// Gérer l'ajout, la mise à jour et la suppression d'un coach
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_coach'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];

        // Validation des données
        if (!empty($nom) && !empty($prenom)) {
            // Ajouter le coach à la base de données
            $query = "INSERT INTO coach (nom, prenom) VALUES ('$nom', '$prenom')";
            $result = $conn->query($query);

            if ($result) {
                echo '<div class="alert alert-success" role="alert">Coach added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error adding coach: ' . $conn->error . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Please enter coach name and first name!</div>';
        }
    } elseif (isset($_POST['update_coach'])) {
        $codeCo = $_POST['codeCo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];

        // Validation des données
        if (!empty($codeCo) && !empty($nom) && !empty($prenom)) {
            // Mettre à jour le coach dans la base de données
            $query = "UPDATE coach SET nom = '$nom', prenom = '$prenom' WHERE codeCo = '$codeCo'";
            $result = $conn->query($query);

            if ($result) {
                echo '<div class="alert alert-success" role="alert">Coach updated successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error updating coach: ' . $conn->error . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Invalid data for update!</div>';
        }
    }
}

// Gérer la suppression d'un coach
if (isset($_GET['delete'])) {
    $deleteCodeCo = $_GET['delete'];

    // Supprimer le coach de la base de données
    $query = "DELETE FROM coach WHERE codeCo = '$deleteCodeCo'";
    $result = $conn->query($query);

    if ($result) {
        echo '<div class="alert alert-success" role="alert">Coach deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting coach: ' . $conn->error . '</div>';
    }
}

// Récupérer la liste des coaches depuis la base de données
$query = "SELECT * FROM coach";
$result = $conn->query($query);

// Gérer la récupération des données pour la mise à jour
if (isset($_GET['edit'])) {
    $editCodeCo = $_GET['edit'];

    // Récupérer les données du coach à éditer
    $editQuery = "SELECT * FROM coach WHERE codeCo = '$editCodeCo'";
    $editResult = $conn->query($editQuery);

    if ($editResult->num_rows > 0) {
        $editRow = $editResult->fetch_assoc();
        $editNom = $editRow['Nom'];
        $editPrenom = $editRow['Prenom'];
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
    <title>Manage Coaches</title>
    <style>
        .coach-list {
            list-style: none;
            padding: 0;
        }

        .coach-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
            margin-bottom: 8px;
            padding: 8px;
            background-color: #f9f9f9;
        }

        .coach-actions {
            display: flex;
        }

        .btn-action {
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="container mt-5">
        <h2>Manage Coaches</h2>

        <!-- Form to add/update a new coach -->
        <form method="post" action="">
            <div class="form-group">
                <label for="nom">Coach's Last Name:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($editNom) ? $editNom : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Coach's First Name:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo isset($editPrenom) ? $editPrenom : ''; ?>" required>
            </div>
            <?php if (isset($editCodeCo)): ?>
                <input type="hidden" name="codeCo" value="<?php echo $editCodeCo; ?>">
                <button type="submit" class="btn btn-primary" name="update_coach">Update Coach</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="add_coach">Add Coach</button>
            <?php endif; ?>
        </form>

        <!-- Display the list of coaches -->
        <?php
        if ($result->num_rows > 0) {
            echo '<ul class="coach-list">';
            while ($row = $result->fetch_assoc()) {
                echo '<li class="coach-item">' . $row['Nom'] . ' ' . $row['Prenom'] . ' 
                        <div class="coach-actions">
                            <a href="coaches.php?edit=' . $row['CodeCo'] . '" class="btn btn-warning btn-sm btn-action">Edit</a>
                            <a href="coaches.php?delete=' . $row['CodeCo'] . '" class="btn btn-danger btn-sm btn-action" onclick="return confirm(\'Are you sure?\')">Delete</a>
                        </div>
                      </li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No coaches found.</p>';
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
