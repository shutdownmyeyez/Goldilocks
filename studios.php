<?php
require_once "components/core.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Наши студии - Goldilocks</title>
</head>
<body>
    <?php
 include "components/header.php";
 include "components/popUp.php";
?>
    <main class="studios-page">
        <div class="container">
            <h1 class="page-title">Наши студии</h1>
            
            <!-- Контейнер для списка студий -->
            <div class="studios-container" id="studiosContainer"></div>
            
            <!-- Детальная информация о студии -->
            <div class="studio-detail" id="studioDetail" style="display: none;">
                <button class="back-btn" onclick="window.history.back()">← Назад</button>
                <img class="studio-detail__image" id="studioImage" alt="Фото студии">
                <div class="studio-info">
                    <h2 class="studio-title" id="studioTitle"></h2>
                    <p class="studio-address" id="studioAddress"></p>
                    <p class="studio-description" id="studioDescription"></p>
                </div>
            </div>
        </div>
    </main>

    <script>
      // Данные студий
      const studiosData = [
        {
            id: 1,
            title: "Студия на Кирова",
            address: "ул. Кирова, 27",
            image: "img/studio_one.svg",
            description: "Флагманская студия с полным спектром услуг"
        },
        {
            id: 2,
            title: "Студия на Крупской",
            address: "ул. Крупской, 14",
            image: "img/studio_two.svg",
            description: "Уютная студия в историческом центре"
        },
        {
            id: 3,
            title: "Студия на Перелёта",
            address: "ул. Перелёта, 27",
            image: "img/studio_three.svg",
            description: "Современный дизайн и новейшее оборудование"
        },
        {
            id: 4,
            title: "Студия на Менделеева",
            address: "ул. Менделеева, 21",
            image: "img/studio_four.svg",
            description: "Премиум-студия с индивидуальными кабинетами"
        }
    ];

    // Инициализация страницы
    function initStudioPage() {
        console.log("Скрипт запущен");
        
        const urlParams = new URLSearchParams(window.location.search);
        const studioId = urlParams.get('id');
        console.log("StudioId from URL:", studioId);

        if (studioId) {
            const studio = studiosData.find(s => s.id.toString() === studioId);
            if (studio) {
                console.log("Найдена студия:", studio);
                showStudioDetail(studio);
                return;
            }
            console.error("Студия не найдена");
        }
        showStudioList();
    }

    // Показать список студий
    function showStudioList() {
        console.log("Показываем список студий");
        const container = document.getElementById('studiosContainer');
        if (!container) {
            console.error("Контейнер studiosContainer не найден");
            return;
        }

        container.innerHTML = studiosData.map(studio => `
            <div class="studio-card">
                <a href="studios.php?id=${studio.id}" class="studio-link">
                    <img src="${studio.image}" alt="${studio.title}">
                    <div class="studio-info">
                        <h3>${studio.title}</h3>
                        <p>${studio.address}</p>
                    </div>
                </a>
            </div>
        `).join('');
    }

    // Показать детали студии
    function showStudioDetail(studio) {
        console.log("Показываем детали студии");
        const container = document.getElementById('studiosContainer');
        const detailElement = document.getElementById('studioDetail');
        
        if (container) container.style.display = 'none';
        if (!detailElement) {
            console.error("Элемент studioDetail не найден");
            return;
        }

        // Заполняем данные
        const imgElement = document.getElementById('studioImage');
        if (imgElement) imgElement.src = studio.image;

        const titleElement = document.getElementById('studioTitle');
        if (titleElement) titleElement.textContent = studio.title;

        const addressElement = document.getElementById('studioAddress');
        if (addressElement) addressElement.textContent = studio.address;

        const descElement = document.getElementById('studioDescription');
        if (descElement) descElement.textContent = studio.description;

        detailElement.style.display = 'block';
    }

    // Запускаем после полной загрузки DOM
    document.addEventListener('DOMContentLoaded', initStudioPage);
    </script>
  <script src="js/script.js"></script>
    <?php
    include "components/footer.php";
    ?>
</body>
</html>
