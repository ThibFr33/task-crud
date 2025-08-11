<?php
require_once "config/database.php";
$pdo = dbConnexion();
//récupération de l'id pour l'affichage et l'update ensuite
$id = (int)($_GET['id'] ?? 0);
//si l'id n'est pas un integer valide alors ça bloque
if ($id <= 0) { 
    die("ID invalide."); 
}

//récupération des arrays et création tableau vide pour y injecter les erreurs
$validStatus     = ["terminée","en cours","à faire"];
$validPriorities = ["basse","moyenne","haute"];
$errors = [];

// si la méthode post a été utilisée alors je vais update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim(htmlspecialchars($_POST['title'] ?? ''));
    $description = trim(htmlspecialchars($_POST['description'] ?? ''));
    $status      = $_POST['status'] ?? '';
    $priority    = $_POST['priority'] ?? '';
    $due_date    = trim(htmlspecialchars($_POST['due_date'] ?? ''));

    if (empty($title)) {
        $errors[] = "Le titre est obligatoire.";
    }
    if (empty($description)) {
        $errors[] = "La description est obligatoire.";
    }
    if (!in_array($status, $validStatus, true)) {        
        $errors[] = "Statut invalide.";
    }
    if (!in_array($priority, $validPriorities, true)) {  
        $errors[] = "Priorité invalide.";
    }
    if (!empty($due_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) {
        $errors[] = "Date invalide (AAAA-MM-JJ).";
    }
        

    // validations
    if (empty($errors)) {
        $updatedTask = $pdo->prepare("UPDATE tasks SET title=?, description=?, status=?, priority=?, due_date=?, updated_at=NOW() WHERE id=?");
        $updatedTask->execute([$title, $description, $status, $priority, empty($due_date) ? null : $due_date,$id]);

        header("Location: show_task.php?id=".$id."&ok=1");
        exit;
    }

}

// affichage de la task depuis la db après ou sans update
$selectedTask = $pdo->prepare("SELECT * FROM tasks WHERE id=?");
$selectedTask->execute([$id]);
$task = $selectedTask->fetch();
if (!$task) { 
    die("Tâche introuvable"); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <title><?= ($task['title']) ?></title>
</head>
<body>
    <main>
        

        <section class="update-task-form">
            <h1><?= ($task["title"]) ?></h1>
            <form action="" method="POST" class="add-task">
                <div class="form-text">
                    <label for="title"> Titre</label>
                    <input type="text" name="title" id="title"  value="<?= ( $task["title"]) ?>" required>
                </div>
                <div class="form-text">
                    <label for="description"> Description</label>
                    <textarea name="description" id="description" required><?= ( $task["description"]) ?></textarea>
                </div>
                <div class="form-text">
                    <label for="status"> Status</label>
                        <select name="status" id="status" required>
                            <?php
                            foreach ($validStatus as $status) {
                                $selectedStatus = ($task["status"] === $status) ? "selected" : "";
                                echo "<option value=\"$status\" $selectedStatus>$status</option>";
                            }
                        ?>
                        </select>
                </div>
                <div class="form-text">
                    <label for="priority"> Priorité </label>
                    <select name="priority" id="priority" required>
                        <?php
                           foreach ($validPriorities as $priority) {
                                $selectedPriority = ($task["priority"] === $priority) ? "selected" : "";
                                echo "<option value=\"$priority\" $selectedPriority>$priority</option>";
                            }
                        ?>
                        </select>

                </div>
                <div class="form-text">
                    <label for="due_date">A faire avant le:</label>
                    <input type="date" name="due_date" id="due_date" value="<?= ( $task["due_date"]) ?>">
                </div>
                <div class="form-text">
                    <input type="submit" value="Mettre à jour">
                </div>
            </form>
            <?php if (!empty($errors)) { ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error) { ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                    <?php } ?>
                </ul>
            <?php }elseif (isset($_GET['ok'])){ ?>
                <p>Modifications enregistrées</p>
            <?php } ?>
        </section>

    </main>
</body>
</html>