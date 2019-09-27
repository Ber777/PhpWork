<form action="" method="POST" id="form_add_catalog_from_template">
    <h2 class="padding-for-h1">Вставить рубрику на основе шаблона</h2>
    <input id="input-name-catalog" name="name_catalog" class="standart-input" type="text"
           placeholder="Название рубрики"/>
    <select name="id_template" class="standart-input" id="select-templ">
        <option selected="" hidden="" value="0">Выберите шаблон</option>
        <? foreach ($this->list_templates as $template): ?>
            <option title="<?= $template['name'] ?>"
                    value="<?= $template['id'] ?>"><?= $template['name'] ?></option>
        <? endforeach; ?>
    </select>
    <p class="block-error _display_none">
    <p>
        <input id="id_parent_hidden" name="id_parent" type="hidden" value="<?= $this->id_current ?>">
        <input type="submit" value="Создать" class="click-button"/>
        <a style="color: red;" class="click-button cancel-popup">Отмена</a>
    </p>
</form>
