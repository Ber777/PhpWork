<?require_once 'profilechange_function.php';?>
	<link rel="stylesheet" href="../../../../libs/bootstrap/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="../../../../libs/magnific-popup/magnific-popup.css">
	
	<link rel="stylesheet" href="../../../../css/fonts.css">
	<link rel="stylesheet" href="../../../../css/main.css">
	<link rel="stylesheet" href="../../../../css/media.css">

	<script src="../../../../libs/jquery/jquery-1.11.2.min.js"></script>
	<!--<script src="libs/waypoints/waypoints.min.js"></script>
	<script src="libs/animate/animate-css.js"></script>
	<script src="libs/plugins-scroll/plugins-scroll.js"></script>-->
	<script src="../../../../libs/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script src="../../../../js/common.js"></script>

    <!--<form method="POST" action="">-->
    <b href="#set-theme-profile" class="popup">Изменить настройки профиля</b>
    <!--<form method = "POST" id="set-theme-profile" action="">-->
    <!--<form method = "POST" id="set-theme-profile" action="<?=$this->id_current_object ?>/">-->
    <!--<h3>Внешние настройки пользователя</h3>-->
    <div class="hidden"> <!-- id="block-form-set-theme-profile"  -->
        <!--<form method = "POST" id="set-theme-profile" action="">-->
	<form method = "POST" id="set-theme-profile">
        <h3>Внешние настройки пользователя</h3>
        <p>Верхняя панель</p>
        <select class="standart-input" name="background_navmenu">
            <option <? echo ($this->fields['background_navmenu'] == "RED") ? 'selected' : '' ?> value="RED">Красный</option>
            <option <? echo ($this->fields['background_navmenu'] == "GRAY") ? 'selected' : '' ?> value="GRAY">Серый</option>
            <option <? echo ($this->fields['background_navmenu'] == "DEFAULT") ? 'selected' : '' ?> value="DEFAULT">Стандартный</option>
        </select>
        <p>Вид карт знаний</p>
        <select class="standart-input" name="image_mapknowledge">
            <option <? echo ($this->fields['image_mapknowledge'] == "/images/6grannik.png") ? 'selected' : '' ?> value="/images/6grannik.png">Шестигранники</option>
            <option <? echo ($this->fields['image_mapknowledge'] == "/images/folder.png") ? 'selected' : '' ?> value="/images/folder.png">Папки</option>
        </select>
        <p>Размер шрифта</p>
        <input class="standart-input" type="number" name="font_size" value="<?=$this->fields['font_size'] ?>">
        <p><input type="submit" value="Применить" class="click-button"></p> 
    <!--</div>-->

</form>   
</div> 
<!--<form id="set-theme-profile" action="/profile/ajaxSetProfile/<?=$this->id_current_object ?>/"> class="click-button-->

<!-- /profile/ajaxSetProfile/<?=$this->id_current_object ?>/ -->


