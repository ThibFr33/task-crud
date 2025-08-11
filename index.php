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
    <main>
        <h1>Ma Task List:</h1> 
        <section class="task-list">
            <?php if(!empty("tasks")){ ?>
                <?php foreach ($tasks as $task){ ?>
                    <div class="task-card">
                        <p><?= htmlspecialchars(trim($task["title"])) ?></p> 
                            <span>Status : <?= ucwords($task["status"]) ?></span>
                            <span>Priorité : <?= ucwords($task["priority"]) ?></span>
                    </div>
                        <?php }?>
            </section>
            <?php } ?>
    </main>
        
</body>
</html>














        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        