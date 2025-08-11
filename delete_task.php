<?php
require_once "config/database.php";

$pdo = dbConnexion();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID invalide.");
}

// suppression de la task
$deletedTask = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$deletedTask->execute([$id]);

// on revient sur l'index
header("Location: index.php?deleted=1");
exit;