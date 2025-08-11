<?php
require_once "config/database.php";

$errors= [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //déclaration des variables avec nettoyage et tri des données
    $title = trim(htmlspecialchars($_POST["title"] ?? ''));
    $description = trim(htmlspecialchars($_POST["description"] ?? ''));
    $status = $_POST["status"] ?? '';
    $priority = $_POST["priority"] ?? '';
    $due_date = trim($_POST["due_date"]  ?? '');

    //vérifications
     if (empty($title)) {
        $errors[] = "Le titre est obligatoire.";
    }
    if (empty($description)) {
        $errors[] = "La description est obligatoire.";
    }

    $validStatus = ["terminée", "en cours", "à faire"];
    if (!in_array($status, $validStatus)) {
        $errors[] = "Le statut est invalide.";
    }

    // vérifications liste déroulante priority
    $validPriorities = ["basse", "moyenne", "haute"];
    if (!in_array($priority, $validPriorities)) {
        $errors[] = "La priorité est invalide.";
    }

    if(!empty($due_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) {
        $errors[] = "La date fournie est invalide.";
    }
    //condition injection d'une nouvelle tâche en db
    if(empty($errors)){
        try {
            $pdo = dbConnexion();
            
            $insertTask = $pdo->prepare("INSERT INTO tasks (title, description, status, priority, due_date) VALUES (?, ?, ?, ?, ?)");

            $insertTask->execute([$title, $description, $status, $priority,$due_date]);

            echo ($successMessage = "Nouvelle tâche ajoutée avec succès ! ");

            header("location:index.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erreur DB :" . $e->getMessage();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/style/style.css">
        <title>Document</title>
    </head>
    <body>
    <?php include 'includes/header.php';?>
    <main>
        <section class="add-task-form">
            <h1>Ajouter une tâche</h1>
            <form action="" method="POST" class="add-task">
                <div class="form-text">
                    <label for="title"> Titre</label>
                    <input type="text" name="title" id="title" placeholder="Insérer un titre de tâche" required>
                </div>
                <div class="form-text">
                    <label for="description"> Description</label>
                    <textarea name="description" id="description" placeholder="Votre description ..." required></textarea>
                </div>
                <div class="form-text">
                    <label for="status"> Status</label>
                        <select name="status" id="status">
                            <option value="terminée">Terminée</option>
                            <option value="en cours">En cours</option>
                            <option value="à faire">A faire</option>
                        </select>
                </div>
                <div class="form-text">
                    <label for="priority"> Priorité </label>
                    <select name="priority" id="priority">
                            <option value="basse">Basse</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="haute">Haute</option>
                        </select>

                </div>
                <div class="form-text">
                    <label for="due_date">A faire avant le:</label>
                    <input type="date" name="due_date" id="due_date">
                </div>
                <div class="form-text">
                    <input type="submit" value="Ajouter">
                </div>
            </form>
        </section>
    </main>
    <?php include 'includes/footer.php';?>
</body>
</html>

    <!-- //title
    //description
    //status
    //priority
    //due_date
    //created_at
    //updated_at -->

