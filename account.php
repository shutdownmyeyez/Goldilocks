<?php
session_start();
require_once "components/core.php"; 
include "components/header.php";
    include "components/popUp.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] !== '3') {
header('Location: /access_denied.php');
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Goldilocks</title>
</head>
<body>
     <section class="admin-panel">
    <div class="container">
        <h2 class="section-title">Личный кабинет администратора</h2>
        
        <div class="admin-content">
            <!-- Форма добавления услуги -->
            <form class="admin-form" action="action/add_service.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="service-name">Название услуги:</label>
                    <input type="text" id="service-name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="service-description">Описание:</label>
                    <textarea id="service-description" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="service-price">Цена:</label>
                    <input type="number" id="service-price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="service-image">Изображение:</label>
                    <input type="file" id="service-image" name="image" accept="image/*" required>
                </div>

                <button type="submit" class="submit-btn">Добавить услугу</button>
            </form>
        </div>
        
    </div>
    
</section>

<section class="admin-panel">
    <div class="container">
        <h2 class="section-title">Модерация отзывов</h2>
        <div class="moderation-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Рейтинг</th>
                        <th>Текст отзыва</th>
                        <th>Изображения</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reviews = $mysqli->query("
                        SELECT r.*, u.login 
                        FROM reviews r
                        LEFT JOIN users u ON r.user_id = u.id
                        WHERE r.status = 'pending'
                        ORDER BY r.created_at DESC
                    ");
                    
                    while($review = $reviews->fetch_assoc()):
                        $images = $mysqli->query("
                            SELECT file_path 
                            FROM review_images 
                            WHERE review_id = {$review['id']}
                        ");
                    ?>
                    <tr data-review-id="<?= $review['id'] ?>">
                        <td><?= $review['id'] ?></td>
                        <td><?= htmlspecialchars($review['login']) ?></td>
                        <td>
                            <div class="rating-stars">
                                <?= str_repeat('★', $review['rating']) ?>
                            </div>
                        </td>
                        <td><?= nl2br(htmlspecialchars($review['comment'])) ?></td>
                        <td>
                            <?php if($images->num_rows > 0): ?>
                                <div class="review-images-preview">
                                    <?php while($image = $images->fetch_assoc()): ?>
                                        <img src="/uploads/reviews/<?= htmlspecialchars($image['file_path']) ?>" 
                                             alt="Превью отзыва" 
                                             class="review-image-thumb">
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <span class="no-images">Нет изображений</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></td>
                        <td>
                            <form method="POST" action="action/moderate_review.php" class="moderation-actions">
                                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                
                                <button type="submit" name="action" value="approve" class="approve-btn">
                                    ✓
                                </button>
                                <button type="submit" name="action" value="reject" class="reject-btn">
                                    ✕
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if($reviews->num_rows === 0): ?>
                    <tr>
                        <td colspan="7" class="no-reviews">Нет отзывов для модерации</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="admin-vacancies">
    <div class="container">
        <h2 class="section-title">Управление вакансиями</h2>
        
        <!-- Форма добавления вакансии -->
        <form class="admin-form" action="action/manage_vacancy.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="vacancy-title">Название вакансии:</label>
                <input type="text" id="vacancy-title" name="title" required>
            </div>

            <div class="form-group">
                <label for="vacancy-salary">Зарплата:</label>
                <input type="text" id="vacancy-salary" name="salary" required>
            </div>

            <div class="form-group">
                <label for="vacancy-description">Описание:</label>
                <textarea id="vacancy-description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="vacancy-requirements">Требования (каждое с новой строки):</label>
                <textarea id="vacancy-requirements" name="requirements" rows="6" required></textarea>
            </div>

            <button type="submit" name="action" value="add" class="submit-btn">Добавить вакансию</button>
        </form>

        <!-- Список существующих вакансий -->
        <div class="vacancies-list">
            <?php
            $vacancies = $mysqli->query("SELECT * FROM vacancies ORDER BY created_at DESC");
            if($vacancies->num_rows > 0):
            ?>
                <?php while($vacancy = $vacancies->fetch_assoc()): ?>
                <div class="vacancy-item" data-id="<?= $vacancy['id'] ?>">
                    <div class="vacancy-header">
                        <h3><?= htmlspecialchars($vacancy['title']) ?></h3>
                        <div class="vacancy-actions">
                            <button class="edit-btn" onclick="editVacancy(<?= $vacancy['id'] ?>)">✎</button>
                            <form action="action/manage_vacancy.php" method="POST">
                                <input type="hidden" name="id" value="<?= $vacancy['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" name="action" value="delete" class="delete-btn">🗑</button>
                            </form>
                        </div>
                    </div>
                    <p class="vacancy-salary"><?= htmlspecialchars($vacancy['salary']) ?></p>
                    <p class="vacancy-description"><?= htmlspecialchars($vacancy['description']) ?></p>
                    <ul class="vacancy-requirements">
                        <?php foreach(json_decode($vacancy['requirements']) as $req): ?>
                        <li><?= htmlspecialchars($req) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-vacancies">Нет активных вакансий</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Модальное окно редактирования вакансии -->
<div class="modal" id="editVacancyModal">
    <div class="modal-content">
        <span class="close" onclick="closeEditVacancyModal()">&times;</span>
        <h3>Редактирование вакансии</h3>
        <form id="editVacancyForm" action="action/manage_vacancy.php" method="POST">
            <input type="hidden" name="id" id="edit_vacancy_id">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="edit">
            
            <div class="form-group">
                <label for="edit_vacancy_title">Название:</label>
                <input type="text" id="edit_vacancy_title" name="title" >
            </div>

            <div class="form-group">
                <label for="edit_vacancy_salary">Зарплата:</label>
                <input type="text" id="edit_vacancy_salary" name="salary" >
            </div>

            <div class="form-group">
                <label for="edit_vacancy_description">Описание:</label>
                <textarea id="edit_vacancy_description" name="description" rows="4" ></textarea>
            </div>

            <div class="form-group">
                <label for="edit_vacancy_requirements">Требования (каждое с новой строки):</label>
                <textarea id="edit_vacancy_requirements" name="requirements" rows="6" ></textarea>
            </div>

            <button type="submit" class="submit-btn">Сохранить изменения</button>
        </form>
    </div>
</div>
<?php
include "components/footer.php";
?>
<script src="js/script.js"></script>
<script src="js/applications.js"></script>
</body>
</html>