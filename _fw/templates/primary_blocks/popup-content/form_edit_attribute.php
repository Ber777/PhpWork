<form id="form-edit-attribute" method="POST" action="#">
    <h2>Форма редактирования атрибута "<?=$this->attribute_info['name'] ?>" (#<?=$this->attribute_info['id'] ?>)</h2>
    <input id="id-attribute" type="hidden" value="<?=$this->attribute_info['id'] ?>">
    <input id="name-attribute"class="standart-input" type="text" value="<?=$this->attribute_info['name'] ?>">
    <select id="type-attribute" class="standart-input" disabled >
        <option <? if ($this->attribute_info['type'] == 2) echo 'selected' ?> value="2">текст</option>
        <option <? if ($this->attribute_info['type'] == 1) echo 'selected' ?> value="1">число</option>
        <option <? if ($this->attribute_info['type'] == 3) echo 'selected' ?> value="3">дата</option>
    </select>
    <p>
        <input class="click-button" type="submit" value="Соханить">
        <a style="color: red;" class="click-button cancel-popup">Отмена</a>
    </p>
</form>