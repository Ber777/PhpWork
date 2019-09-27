<? if (count($this->array_sort_alphabet)): ?>
    <ul class="list-attributes _padding-for-h1">
        <? foreach ($this->array_attributes as $key => $attribute): ?>
            <li>
                <div class="block-list-items">
                    <? $this->AdminPanelObject('attribute', $key); ?>
                    <p><?= $attribute['name']; ?></p>
                    <span><i>Тип :: <?=_fw_translate_type_attribute($attribute['type'], 'rus') ; ?></i></span>
                </div>
            </li>
        <? endforeach; ?>
    </ul>
<? else: ?>
    <h3>Атрибуты отсутствуют</h3>
<? endif; ?>