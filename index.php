<?php
require_once "config/database.php";
$pdo = dbConnexion();
$sql = "SELECT *FROM tasks ORDER BY 
    CASE priority
      WHEN 'haute'   THEN 1
      WHEN 'moyenne' THEN 2
      WHEN 'basse'   THEN 3
      ELSE 4
    END ASC,
    due_date ASC,
    created_at DESC
";
$tasks = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

        <title>Task-crud</title>
    </head>
    <body>
    <?php include 'includes/header.php';?>
    <main> 
        <section class="task-list">
            <?php if(!empty($tasks)){ ?>
                <?php foreach ($tasks as $task){ ?>
                    <div class="task-card">
                        <div class="delete-icon">
                            <a href="delete_task.php?id=<?= $task['id'] ?>" 
                            onclick="return confirm('Voulez-vous vraiment supprimer cette tâche ?')"><i class="fa-solid fa-trash"></i></a>

                        </div>
                        <p><?= htmlspecialchars(trim($task["title"])) ?></p> 
                        <div class="task-card-infos">
                            <span>Status : <?= ucwords($task["status"]) ?></span>
                            <span>Priorité : <?= ucwords($task["priority"]) ?></span>
                            <span>Echéance : <?= ($task["due_date"]) ?></span>
                        </div>
                        <a class="d-none" href="show_task.php?id=<?= (int)$task['id'] ?>">Voir / Modifier</a>
                    </div>
                        <?php }?>
            </section>
            <?php } ?>
    </main>
    <?php include 'includes/footer.php';?>
</body>
</html>














        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        