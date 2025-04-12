<?php
require_once "core.php";
$services_result = $mysqli->query("SELECT id, name FROM services");
$services = [];
if($services_result) {
    while($row = $services_result->fetch_assoc()) {
        $services[] = $row;
    }
}
if(isset($_SESSION['errors'])): ?>
    <div class="errors">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
    <div class="success">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif;
?>

<div class="container">
    <div class="popUp"> 
        <div class="popUp__form">  
            <img src="img/close.svg" alt="" class="popUp__closeBtn_item" onclick="closeModal()">
            <div class="popUp__form_content">
                <form id="applicationForm" action="../action/submit_application.php" method="POST">
                    <div class="form-group">
                        <label for="fullname">ФИО:</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Дата:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="service">Услуга:</label>
                        <select id="service" name="service_id" required>
                            <option value="">Выберите услугу</option>
                            <?php foreach($services as $service): ?>
                                <option value="<?= $service['id'] ?>">
                                    <?= htmlspecialchars($service['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Отправить</button>
                </form>
            </div>
        </div>
    </div>
</div>