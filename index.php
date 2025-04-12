<?php
session_start();
require_once "components/core.php";
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
    <?php
    include "components/popUp.php";
    include "components/header.php";
    
    ?>
    <section class="content">
<section class="content">
    <div class="container">
        <div class="masters-slider">
            <div class="slider-container">
                <!-- Слайд 1 -->
                <div class="slide active">
                    <div class="slide-content">
                        <img src="img/poster_one.svg" alt="Мастер 1" class="slide-poster">
                        <div class="master-info">
                            <div class="master-content">
                                <h2 class="master-title">Мастера</h2>
                                <h3 class="master-name">Анна Иванова</h3>
                                <p class="master-specialty">Парикмахер</p>
                                <p class="master-description">Опыт работы 8 лет. Специализация: окрашивание, уходовые процедуры, креативные стрижки.</p>
                                
                                <?php if(isset($_SESSION['user'])): ?>
                                <button class="master-btn" onclick="openModal()">Записаться</button>
                                <?php else: ?>
                                <a href="signUp.php" class="master-btn">Записаться</a>
                                <?php endif; ?>
                            </div>
                            <img src="img/master_one.svg" alt="Фото Анны Ивановой" class="master-photo">
                        </div>
                    </div>
                </div>

                <div class="slide">
                    <div class="slide-content">
                        <img src="img/poster_two.svg" alt="Мастер 2" class="slide-poster">
                        <div class="master-info">
                            <div class="master-content">
                                <h2 class="master-title">Мастера</h2>
                                <h3 class="master-name">Варвара Петрова</h3>
                                <p class="master-specialty">Парикмахер</p>
                                <p class="master-description">Опыт работы 5 лет. Специализация: окрашивание, уходовые процедуры, креативные стрижки.</p>
                                <!-- Сделайте -->
                                <?php if(isset($_SESSION['user'])): ?>
                                <button class="master-btn" onclick="openModal()">Записаться</button>
                                <?php else: ?>
                                <a href="signUp.php" class="master-btn">Записаться</a>
                                <?php endif; ?>
                            </div>
                            <img src="img/master_two.svg" alt="Фото Анны Ивановой" class="master-photo">
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide-content">
                        <img src="img/poster_three.svg" alt="Мастер 3" class="slide-poster">
                        <div class="master-info">
                            <div class="master-content">
                                <h2 class="master-title">Мастера</h2>
                                <h3 class="master-name">Алина Гречнева</h3>
                                <p class="master-specialty">Парикмахер</p>
                                <p class="master-description">Опыт работы 11 лет. Специализация: окрашивание, уходовые процедуры, креативные стрижки.</p>
                                <?php if(isset($_SESSION['user'])): ?>
                                <button class="master-btn" onclick="openModal()">Записаться</button>
                                <?php else: ?>
                                <a href="signUp.php" class="master-btn">Записаться</a>
                                <?php endif; ?>
                            </div>
                            <img src="img/master_three.svg" alt="Фото Анны Ивановой" class="master-photo">
                        </div>
                    </div>
                </div>

                <!-- Кнопки навигации -->
                <button class="slider-btn prev-btn">‹</button>
                <button class="slider-btn next-btn">›</button>
            </div>
        </div>
    </div>
</section>
<?php
if($services->num_rows === 0):
?>
<div class="no-services">
    <p>Услуги временно отсутствуют</p>
</div>
<?php else: ?>
<!-- Остальной код -->

<section class="pricing">
    <div class="container">
        <h2 class="section-title">Наши услуги</h2>
        <div class="pricing__content">
            <!-- Колонка с миниатюрами -->
            <div class="services-list">
                <?php
                $services = $mysqli->query("SELECT * FROM services");
                $active = true;
                while($service = $services->fetch_assoc()):
                ?>
                <div class="service-item <?= $active ? 'active' : '' ?>" data-service="<?= $service['id'] ?>">
                    <img src="img/services/<?= $service['image'] ?>" alt="<?= $service['name'] ?>" class="service-thumb">
                </div>
                <?php 
                    $active = false;
                    endwhile; 
                ?>
            </div>

            <!-- Блок с описанием -->
            <div class="service-details">
                <?php
                $services->data_seek(0); // Сбрасываем указатель результата
                $active = true;
                while($service = $services->fetch_assoc()):
                ?>
                <div class="detail-card <?= $active ? 'active' : '' ?>" data-service="<?= $service['id'] ?>">
                    <h3 class="detail-title"><?= $service['name'] ?></h3>
                    <p class="detail-text"><?= $service['description'] ?></p>
                    <p class="detail-price">от <?= number_format($service['price'], 0, '', ' ') ?> ₽</p>
                    <!-- Сделайте -->
<?php if(isset($_SESSION['user'])): ?>
    <button class="detail-btn" onclick="openModal()">Записаться</button>
<?php else: ?>
    <a href="signUp.php" class="detail-btn">Записаться</a>
<?php endif; ?>
                </div>
                <?php 
                    $active = false;
                    endwhile; 
                ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<section class="reviews">
    <div class="container">
        <h2 class="section-title">Отзывы клиентов</h2>
        
        <div class="reviews-slider">
            <div class="slider-container">
                <?php
                $result = $mysqli->query("
                    SELECT r.*, u.login as user_name 
                    FROM reviews r
                    LEFT JOIN users u ON r.user_id = u.id
                    WHERE r.status = 'approved'
                    ORDER BY r.created_at DESC
                    LIMIT 5
                ");
                
                if ($result->num_rows > 0):
                    while($review = $result->fetch_assoc()):
                        $images = $mysqli->query("
                            SELECT file_path 
                            FROM review_images 
                            WHERE review_id = {$review['id']}
                        ");
                ?>
                <div class="review-slide">
                    <div class="review-header">
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars($review['user_name']) ?></span>
                            <div class="rating-stars">
                                <?= str_repeat('★', $review['rating']) ?>
                            </div>
                        </div>
                        <span class="review-date"><?= date('d.m.Y', strtotime($review['created_at'])) ?></span>
                    </div>
                    
                    <?php if($images->num_rows > 0): ?>
<div class="review-photos">
    <?php while($image = $images->fetch_assoc()): 
        $fullPath = 'https://goldilocks/' . $image['file_path'];
    ?>
    <div class="photo-item">
        <img src="<?= $fullPath ?>" 
             alt="Фото отзыва" 
             class="review-photo"
             onerror="this.style.display='none'">
    </div>
    <?php endwhile; ?>
</div>
<?php endif; ?>
                    
                    <p class="review-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="no-reviews">Пока нет одобренных отзывов</div>
                <?php endif; ?>
            </div>

            <button class="slider-btn prev-btn">‹</button>
            <button class="slider-btn next-btn">›</button>
        </div>
<?php if (isset($_SESSION['user'])): ?>
    <button class="add-review-btn" onclick="openReviewModal()">Оставить отзыв</button>
<?php endif; ?>
    </div>
</section>
<!-- Модальное окно для отзыва -->
<div class="review-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeReviewModal()">&times;</span>
        <h3>Написать отзыв</h3>
        <!-- В модальном окне -->
<form id="reviewForm" action="action/submit_review.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Оценка:</label>
        <div class="rating-stars-input">
            <?php for($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                <label for="star<?= $i ?>" class="star">★</label>
            <?php endfor; ?>
        </div>
    </div>

    <div class="form-group">
        <label>Фотографии:</label>
        <input type="file" name="images[]" multiple accept="image/*">
    </div>

    <div class="form-group">
        <label>Текст отзыва:</label>
        <textarea name="comment" rows="5" required></textarea>
    </div>

    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="submit-btn">Отправить</button>
</form>
    </div>
</div>

<?php
include "components/footer.php";
?>
    <script src="js/script.js"></script>
</body>
</html>

