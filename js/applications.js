function openEditModal(applicationId) {
    const modal = document.getElementById('editModal');
    const row = document.querySelector(`tr[data-application-id="${applicationId}"]`);
    
    // Заполнение данных из таблицы
    document.getElementById('application_id').value = applicationId;
    document.getElementById('edit_service').value = row.querySelector('td:first-child').dataset.serviceId;
    document.getElementById('edit_date').value = new Date(row.children[1].textContent.split('.').reverse().join('-')).toISOString().split('T')[0];
    document.getElementById('edit_fullname').value = row.children[2].textContent;
    
    modal.style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Закрытие при клике вне модалки
window.onclick = function(e) {
    const modal = document.getElementById('editModal');
    if(e.target == modal) {
        modal.style.display = 'none';
    }
}

// Редактирование вакансии
function editVacancy(id) {
    const vacancyItem = document.querySelector(`.vacancy-item[data-id="${id}"]`);
    
    document.getElementById('edit_vacancy_id').value = id;
    document.getElementById('edit_vacancy_title').value = vacancyItem.querySelector('.vacancy-header h3').textContent;
    document.getElementById('edit_vacancy_salary').value = vacancyItem.querySelector('.vacancy-salary').textContent;
    document.getElementById('edit_vacancy_description').value = vacancyItem.querySelector('.vacancy-description').textContent;
    
    const requirements = Array.from(vacancyItem.querySelectorAll('.requirements-list li'))
        .map(li => li.textContent.trim()).join('\n');
    document.getElementById('edit_vacancy_requirements').value = requirements;

    document.getElementById('editVacancyModal').style.display = 'block';
}

function closeEditVacancyModal() {
    document.getElementById('editVacancyModal').style.display = 'none';
}

// Обновим общий обработчик клика
window.onclick = function(e) {
    const vacancyModal = document.getElementById('editVacancyModal');
    if(e.target == vacancyModal) {
        vacancyModal.style.display = 'none';
    }
    
    const appModal = document.getElementById('editModal');
    if(e.target == appModal) {
        appModal.style.display = 'none';
    }
}