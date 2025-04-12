<?php
session_start();
require_once "components/core.php";
checkAuth(); // Проверка авторизации
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Мои заявки - Goldilocks</title>
</head>
<body>
<?php include "components/header.php"; ?>

<section class="user-applications">
    <div class="container">
        <h2 class="section-title">Мои заявки</h2>
        
        <div class="applications-list">
            <?php
            $user_id = $_SESSION['user']['id'];
            $query = "
                SELECT s.*, sv.name as service_name 
                FROM states s
                JOIN services sv ON s.service_id = sv.id
                WHERE s.user_id = ?
                ORDER BY s.date DESC
            ";
            
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0): ?>
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>Услуга</th>
                            <th>Дата</th>
                            <th>Имя</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr data-application-id="<?= $row['id'] ?>">
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td><?= date('d.m.Y', strtotime($row['date'])) ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td>
                                <button class="edit-btn" onclick="openEditModal(<?= $row['id'] ?>)">✎</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-applications">У вас пока нет активных заявок</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Модальное окно редактирования -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Редактирование заявки</h3>
        <form id="editForm" action="action/update_application.php" method="POST">
            <input type="hidden" name="application_id" id="application_id">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="edit_service">Услуга:</label>
                <select name="service_id" id="edit_service" class="form-select" required>
                    <?php
                    $services = $mysqli->query("SELECT id, name FROM services");
                    while($service = $services->fetch_assoc()):
                    ?>
                    <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="edit_date">Дата:</label>
                <input type="date" id="edit_date" name="date" required>
            </div>

            <div class="form-group">
                <label for="edit_fullname">Имя:</label>
                <input type="text" id="edit_fullname" name="fullname" required>
            </div>

            <button type="submit" class="submit-btn">Сохранить изменения</button>
        </form>
    </div>
</div>

<?php include "components/footer.php"; ?>
<script src="js/script.js"></script>
<script src="js/applications.js"></script>
</body>
</html>