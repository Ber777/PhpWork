<div id="work-panel-with-object">
    <ul class="work-panel">
        <? if ($this->user_info['add_document']): ?>
        <li class="click-button">
            <a href="/document/templates/<?=$this->id_current_object ?>" class="hover-underline add-file-in">Добавить документ</a>
        </li>
        <? endif ?>
        <? if ($this->user_info['add_catalog']): ?>
        <li class="click-button">
            <a href="/catalog/add/<?=$this->id_current_object ?>" class="hover-underline add-folder-in">Добавить рубрику</a>
        </li>

        <li class="click-button">
            <a id="link-insert-catalog-from-template" class="hover-underline add-folder-in " idparent="<?=$this->id_current_object ?>">Вставить рубрику из шаблона</a>
        </li>
        <? endif ?>
        <li class="click-button">
            <a id="create-template-from-catalog" class="hover-underline add-file-in ">Создать шаблон на основе рубрики</a>
            <div id="insert-template-from-catalog" class="_display_none">
                <h2>Создать шаблон на основе рубрики "<?=$this->name_current_object ?>"</h2>
                <p class="msg"></p>
                <form id="form-add-template-from-template" method="POST">
                    <input id="name_template" class="standart-input" placeholder="Название шаблона">
                    <input id="id_catalog" type="hidden" value="<?=$this->id_current_object ?>">
                    <p>
                        <input class="click-button" type="submit" value="Создать шаблон">
                        <a style="color: red;" class="cancel-popup click-button">Закрыть</a>
                    </p>
                </form>
            </div>
        </li>
        <br>
        <? if ($this->button_insert): ?>
        <li class="click-button">
            <a class="insert-from-buffer" object="<?=$this->name_object ?>" id_parent="<?=$this->id_current_object ?>">Скопировать из буфера</a>
        </li>
        <? endif ?>
    </ul>
</div>
