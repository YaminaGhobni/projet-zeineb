<?php
include('db.php');

// // Démarrer la session
// session_start();

// // Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['user'])) {
//     header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
//     exit();
// }

// Gérer l'ajout d'une nouvelle séance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_session'])) {
        $codeAct = $_POST['codeAct'];
        $codeCo = $_POST['codeCo'];
        $heureDeb = $_POST['heureDeb'];
        $heureFin = $_POST['heureFin'];

        // Validation des données (vous devrez peut-être ajouter des vérifications supplémentaires)
        if (!empty($codeAct) && !empty($codeCo) && !empty($heureDeb) && !empty($heureFin)) {
            // Ajouter la séance à la base de données
            $query = "INSERT INTO seance (CodeAct, CodeCo, HeureDeb, HeureFin) VALUES ('$codeAct', '$codeCo', '$heureDeb', '$heureFin')";
            $result = $conn->query($query);

            if ($result) {
                echo '<div class="alert alert-success" role="alert">Session added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error adding session: ' . $conn->error . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Please enter all session details!</div>';
        }
    }
}

// Récupérer la liste des séances depuis la base de données
$query = "SELECT * FROM seance";
$result = $conn->query($query);

// Gérer la suppression d'une séance
if (isset($_GET['delete'])) {
    $deleteCodeAct = $_GET['delete'];

    // Supprimer la séance de la base de données
    $deleteQuery = "DELETE FROM seance WHERE CodeAct = '$deleteCodeAct'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        echo '<div class="alert alert-success" role="alert">Session deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting session: ' . $conn->error . '</div>';
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
    <title>Manage Sessions</title>
    <style>
        .session-list {
            list-style: none;
            padding: 0;
        }

        .session-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
            margin-bottom: 8px;
            padding: 8px;
            background-color: #f9f9f9;
        }

        .session-actions {
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
        <h2>Manage Sessions</h2>

        <!-- Form to add a new session -->
<form method="post" action="">
    <div class="form-group">
        <label for="codeAct">Activity Code:</label>
        <select class="form-control" id="codeAct" name="codeAct" required>
            <?php
            // Récupérer la liste des codes d'activité depuis la base de données
            $activityQuery = "SELECT CodeAct FROM activity";
            $activityResult = $conn->query($activityQuery);

            // Afficher les options de la liste déroulante
            while ($activityRow = $activityResult->fetch_assoc()) {
                echo '<option value="' . $activityRow['CodeAct'] . '">' . $activityRow['CodeAct'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="codeCo">Coach Code:</label>
        <select class="form-control" id="codeCo" name="codeCo" required>
            <?php
            // Récupérer la liste des codes de coach depuis la base de données
            $coachQuery = "SELECT CodeCo FROM coach";
            $coachResult = $conn->query($coachQuery);

            // Afficher les options de la liste déroulante
            while ($coachRow = $coachResult->fetch_assoc()) {
                echo '<option value="' . $coachRow['CodeCo'] . '">' . $coachRow['CodeCo'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="heureDeb">Start Time:</label>
        <input type="text" class="form-control" id="heureDeb" name="heureDeb" required>
    </div>
    <div class="form-group">
        <label for="heureFin">End Time:</label>
        <input type="text" class="form-control" id="heureFin" name="heureFin" required>
    </div>
    <button type="submit" class="btn btn-primary" name="add_session">Add Session</button>
</form>

        <!-- Display the list of sessions -->
        <?php
        if ($result->num_rows > 0) {
            echo '<ul class="session-list">';
            while ($row = $result->fetch_assoc()) {
                echo '<li class="session-item">' . $row['CodeAct'] . ' | ' . $row['CodeCo'] . ' | ' . $row['HeureDeb'] . ' - ' . $row['HeureFin'] . ' 
                        <div class="session-actions">
                            <a href="sessions.php?delete=' . $row['CodeAct'] . '" class="btn btn-danger btn-sm btn-action" onclick="return confirm(\'Are you sure?\')">Delete</a>
                        </div>
                      </li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No sessions found.</p>';
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
