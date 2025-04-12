<?php
?>
<header class="header">
        <div class="container">
            <div class="header__row">
                <div class="header__row_left">
                    <div class="header__row_logo">
                        <a href="index.php" class="header__logo_link"><img src="img/logo.svg" alt="" class="header__logo_img">Goldilocks</a>
                    </div>
                    <div class="header__row_menu">
                        <a href="" class="header__menu_btn">
                            <img src="img/buttonMenu.svg" alt="" class="header__menu_btn-img">
                        </a>
                        <ul class="header__menu">
                            <li class="header__menu_item"><a href="vacancy.php" class="header__menu_link">Вакансии</a></li>
                            <li class="header__menu_item"><a href="studios.php" class="header__menu_link">Студии</a></li>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['role_id'] === '3'): ?>
                            <li class="header__menu_item"><a href="account.php" class="header__menu_link">Админ-Панель</a></li>
                            <?php endif;?>
                            <?php if(isset($_SESSION['user'])):   ?>
                            <li class="header__menu_item"><a href="states.php" class="header__menu_link">Заявки</a></li>
                            <?php endif;?>
                    
                        </ul>
                    </div>
                </div>
                <div class="header__row_right">
                    <div class="header__row_contacts">
                        <a href="tel:21-81-51" class="header__contacts_phone"><img src="img/phone.svg" alt="" class="header__contacts_phone-img">21-81-51</a>
                        <p class="header__contacts_text">ежедневно, с 10-21</p>
                    </div>
                    <?php if(!isset($_SESSION['user'])): ?>
                    <div class="header__row_login">
                        <a href="reg.php" class="header__login_item">
                            <img src="img/lk.svg" alt="" class="header__login_img">
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="header__row_btn">
                        <a href="#" class="header__btn_item" onclick="openModal()">Записаться</a>
                    </div>
                    <div class="header__row_btn_logout">
                    <a href="action/logout.php"><img src="img/logout.svg" alt="" class="header__logout_img"></a>
                    </div>
                    <?php endif; ?>
                </div>
           
            </div>
            </div>
        </div>
    </header>