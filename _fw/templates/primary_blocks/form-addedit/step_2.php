<h4 class="title-step" action="show"><span class="click-button"">показать</span>Шаг 2. Выберите набор аттрибутов </h4>
<div class="block-step">
    <div class="col center-in-div">
        <h3>Атрибуты</h3>
        <h5>поле для поиска</h5>
        <select id="sel-attr" multiple="multiple" size="10">
            <? foreach ($this->array_attributes as $item): ?>
            <option title="<?=$item['name'] ?>" option-type="attribute" data-type="<?=_fw_translate_type_attribute($item["type"], 'string') ?>" idattr="<?=$item['id'] ?>"><?=$item['name'] ?></option>
            <? endforeach; ?>
        </select>
        <p style="margin-top: 5px;">
            <select class="standart-input" id="type-attr">
                <option hidden="" selected="" value="none">Выберите тип данных:</option>
                <option value="text">Текст</option>
                <option value="number">Число</option>
                <option value="date">Дата</option>
            </select>
        </p>
        <p style="margin-top: 5px;">
            <input class="standart-input add-in-list-sel2 " id="val-attr" type="text" placeholder="Cвой атрибут">
            <a id="button-add-in-list-sel2-attribute" class="button-add-in-list-result click-button"><img width="32" src="/images/plus.png"></a>
        </p>
    </div>

    <div class="col center-in-div" id="transition-to-create">
        <a class="move click-button transition1" out="sel-attr" in="result-attrs" title="Перенести в набор"><img src="/images/sign-out.png"></a><br>
        <a class="move click-button transition1" out="result-attrs" in="sel-attr" title="Вернуть из набора"><img src="/images/sign-in.png"></a><br>
    </div>
    <div class="col center-in-div">
        <h3>Результат набора аттрибутов</h3>
        <select id="result-attrs" multiple="" name="result-attrs" size="10">
        </select>
        <p><a class="delete-from-result click-button" data-from="result-attrs">Удалить</a></p>
    </div>

</div>
