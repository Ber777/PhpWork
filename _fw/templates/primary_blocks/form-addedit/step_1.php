<h4 class="title-step" action="show"><span class="click-button">показать</span>Шаг 1. Выберите набор дескрипторов</h4>
<div class="block-step">
    <div class="col center-in-div">
        <h3>Дескрипторы</h3>
        <h5>поле для поиска</h5>
        <select id="sel-tag" multiple="multiple" size="10">
            <? foreach ( $this->array_tags as $item): ?>
            <option title="<?=$item['name'] ?>" option-type="tag" idtag="<?=$item['id'] ?>"><?=$item['name'] ?></option>
            <? endforeach ?>
        </select>
        <p style="margin-top: 10px;">
            <input class="standart-input" id="value-tag" type="text" placeholder="Добавьте свой дескриптор">
            <a id="button-add-in-list-sel2-teg" class="button-add-in-list-result click-button"><img width="32" src="/images/plus.png"></a>
        </p>
    </div>
    <div class="col center-in-div" id="transition-to-create">
        <a class="move click-button transition1" out="sel-tag" in="result-tags" title="Перенести в набор"><img src="/images/sign-out.png"></a><br>
        <a class="move click-button transition1" out="result-tags" in="sel-tag" title="Вернуть из набора"><img src="/images/sign-in.png"></a><br>
    </div>
    <div class="col center-in-div">
        <h3>Результат набора тегов</h3>
        <select id="result-tags" multiple="" name="result-tags" size="10">
        </select>
        <p><a class="delete-from-result click-button" data-from="result-tags">Удалить свои дескрипторы</a></p>
    </div>
</div>