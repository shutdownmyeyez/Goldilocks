let btnMenu = document.querySelector('.header__menu_btn-img');
let menu = document.querySelector(".header__menu");
btnMenu.addEventListener('click',function(e){
    e.preventDefault();
    btnMenu.classList.toggle('active');
    menu.classList.toggle('active');  
});

function openModal() {
    document.querySelector('.popUp').style.display = 'flex';
}

function closeModal() {
    document.querySelector('.popUp').style.display = 'none';
    document.getElementById('applicationForm').reset();
}

function handleSubmit(event) {
    event.preventDefault();
    
    const fullname = document.getElementById('fullname').value;
    const date = document.getElementById('date').value;
    const service = document.getElementById('service').value;

    // Здесь можно добавить отправку данных на сервер
    console.log({ fullname, date, service });
    
    closeModal();
    alert('Заявка успешно отправлена!');
}

// Закрытие по клику вне формы
document.querySelector('.popUp').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
}

// Инициализация первого слайда
showSlide(0);

document.querySelector('.next-btn').addEventListener('click', () => showSlide(currentSlide + 1));
document.querySelector('.prev-btn').addEventListener('click', () => showSlide(currentSlide - 1));

// Инициализация прайс-листа
document.querySelectorAll('.service-item').forEach(item => {
    item.addEventListener('click', function() {
        // Удаляем активный класс у всех элементов
        document.querySelectorAll('.service-item').forEach(i => i.classList.remove('active'));
        // Добавляем активный класс текущему элементу
        this.classList.add('active');
        
        // Получаем номер услуги
        const serviceId = this.dataset.service;
        // Скрываем все карточки
        document.querySelectorAll('.detail-card').forEach(card => card.classList.remove('active'));
        // Показываем нужную карточку
        document.querySelector(`.detail-card[data-service="${serviceId}"]`).classList.add('active');
    });
});

// Активируем первую услугу по умолчанию
document.querySelector('.service-item').click();


let currentReview = 0;
const reviewSlides = document.querySelectorAll('.review-slide');

function showReview(index) {
    reviewSlides.forEach(slide => {
        slide.classList.remove('active');
        slide.style.transform = 'translateX(100%)';
    });
    
    currentReview = (index + reviewSlides.length) % reviewSlides.length;
    reviewSlides[currentReview].classList.add('active');
    reviewSlides[currentReview].style.transform = 'translateX(0)';
}

document.querySelector('.reviews-slider .prev-btn').addEventListener('click', () => showReview(currentReview - 1));
document.querySelector('.reviews-slider .next-btn').addEventListener('click', () => showReview(currentReview + 1));

// Модальное окно
function openReviewModal() {
    document.querySelector('.review-modal').style.display = 'flex';
}

function closeReviewModal() {
    document.querySelector('.review-modal').style.display = 'none';
}

document.querySelectorAll('.rating-stars-input').forEach(container => {
    const stars = container.querySelectorAll('input[type="radio"]');
    stars.forEach(star => {
        star.addEventListener('change', () => {
            // Подсвечиваем выбранные звезды
            stars.forEach(s => {
                const label = container.querySelector(`label[for="${s.id}"]`);
                label.style.color = s.checked || s === star ? '#ffd700' : '#ddd';
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.reviews-slider .slider-container');
    const prevBtn = document.querySelector('.reviews-slider .prev-btn');
    const nextBtn = document.querySelector('.reviews-slider .next-btn');
    let currentPosition = 0;

    nextBtn.addEventListener('click', () => {
        const slideWidth = slider.firstElementChild.offsetWidth + 20;
        currentPosition = Math.min(currentPosition + slideWidth, slider.scrollWidth - slider.offsetWidth);
        slider.scrollTo({ left: currentPosition, behavior: 'smooth' });
    });

    prevBtn.addEventListener('click', () => {
        const slideWidth = slider.firstElementChild.offsetWidth + 20;
        currentPosition = Math.max(currentPosition - slideWidth, 0);
        slider.scrollTo({ left: currentPosition, behavior: 'smooth' });
    });
});

// Закрытие модалки при клике вне области
window.onclick = function(e) {
    if(e.target.classList.contains('review-modal')) {
        closeReviewModal();
    }
}

// Инициализация
showReview(0);

// Добавьте закрытие модалки после успешной отправки
document.getElementById('reviewForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(e.target.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            closeReviewModal();
            window.location.reload(); // Обновить список отзывов
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Инициализация Яндекс.Карты
function initYandexMap() {
    ymaps.ready(function() {
        const map = new ymaps.Map('yandex-map', {
            center: [55.751574, 37.573856], // Координаты центра карты
            zoom: 11,
            controls: ['zoomControl']
        });

        // Добавление меток
        const places = [
            {
                coords: [55.751574, 37.573856],
                title: 'ул.Кирова 27',
                content: 'Наш салон на Кирова'
            },
            {
                coords: [55.754933, 37.621093],
                title: 'ул.Крупской 14',
                content: 'Студия на Крупской'
            },
            {
                coords: [55.749542, 37.539356],
                title: 'ул.Перелета 27',
                content: 'Филиал на Перелета'
            },
            {
                coords: [55.744522, 37.607579],
                title: 'ул.Менделеева 21',
                content: 'Студия премиум-класса'
            }
        ];

        places.forEach(place => {
            const marker = new ymaps.Placemark(
                place.coords,
                {
                    hintContent: place.title,
                    balloonContent: place.content
                },
                {
                    iconLayout: 'default#image',
                    iconImageHref: 'img/map-marker.svg',
                    iconImageSize: [40, 40],
                    iconImageOffset: [-20, -40]
                }
            );
            map.geoObjects.add(marker);
        });

        // Адаптив карты
        window.addEventListener('resize', () => map.container.fitToViewport());
    });
}

// Подключение API Яндекс.Карт
const script = document.createElement('script');
script.src = 'https://api-maps.yandex.ru/2.1/?apikey=1465583a-af7c-4d6e-87c8-4304bae37a6a&lang=ru_RU';
script.onload = initYandexMap;
document.head.appendChild(script);

// Инициализация страницы
function initStudioPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const studioId = urlParams.get('id');
    
    if (studioId) {
        const studio = studiosData.find(s => s.id == studioId);
        if (studio) {
            showStudioDetail(studio);
            return;
        }
    }
    showStudioList();
}

// Открытие модалки редактирования
function openEditModal(id) {
    fetch(`action/get_service.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editDescription').value = data.description;
            document.getElementById('editPrice').value = data.price;
            document.getElementById('currentImagePreview').src = `img/services/${data.image}`;
            document.getElementById('editModal').style.display = 'block';
        });
}

// Закрытие модалки
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Удаление услуги
function deleteService(id) {
    if(confirm('Вы уверены, что хотите удалить эту услугу?')) {
        fetch(`action/delete_service.php?id=${id}`)
            .then(response => location.reload());
    }
}

// Обработка формы редактирования
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('action/update_service.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if(response.ok) location.reload();
    });
});