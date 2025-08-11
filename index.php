<?php
require_once "config/database.php";
$pdo = dbConnexion();
$tasks = $pdo->prepare("SELECT * FROM tasks");
$tasks->execute();
$tasks = $tasks->fetchAll();

//  <!-- //title
//     //description
//     //status
//     //priority
//     //due_date
//     //created_at
//     //updated_at -->



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/style/style.css">
        <title>Task-crud</title>
    </head>
    <body>
    <?php include 'includes/header.php';?>
    <main> 
        <section class="task-list">
            <?php if(!empty($tasks)){ ?>
                <?php foreach ($tasks as $task){ ?>
                    <div class="task-card">
                        <p><?= htmlspecialchars(trim($task["title"])) ?></p> 
                            <span>Status : <?= ucwords($task["status"]) ?></span>
                            <span>Priorité : <?= ucwords($task["priority"]) ?></span>
                            <a class="d-none" href="show_task.php?id=<?= (int)$task['id'] ?>">Voir / Modifier</a>

                    </div>
                        <?php }?>
            </section>
            <?php } ?>
    </main>
    <?php include 'includes/footer.php';?>
</body>
</html>














        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        