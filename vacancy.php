<?php
require_once "components/core.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Вакансии - Goldilocks</title>
</head>
<body>
<?php include "components/header.php"; ?>

<section class="public-vacancies">
    <div class="container">
        <h1 class="page-title">Карьерные возможности</h1>
        
        <div class="vacancies-container">
            <?php
            $vacancies = $mysqli->query("
                SELECT * 
                FROM vacancies 
                ORDER BY created_at DESC
            ");
            
            if($vacancies->num_rows > 0):
                while($vacancy = $vacancies->fetch_assoc()):
                    $requirements = json_decode($vacancy['requirements']);
            ?>
            <article class="vacancy-card">
                <div class="vacancy-head">
                    <h2 class="vacancy-position"><?= htmlspecialchars($vacancy['title']) ?></h2>
                    <div class="vacancy-salary"><?= htmlspecialchars($vacancy['salary']) ?></div>
                </div>
                
                <div class="vacancy-body">
                    <p class="vacancy-description"><?= htmlspecialchars($vacancy['description']) ?></p>
                    
                    <div class="vacancy-requirements">
                        <h3>Требования:</h3>
                        <ul class="requirements-list">
                            <?php foreach($requirements as $req): ?>
                            <li class="requirement-item"><?= htmlspecialchars($req) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else: ?>
            <div class="no-vacancies">
                <p>В настоящее время открытых вакансий нет</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include "components/footer.php";?>
<script src="js/script.js"></script>
<script src="js/applications.js"></script>

</body>
</html>