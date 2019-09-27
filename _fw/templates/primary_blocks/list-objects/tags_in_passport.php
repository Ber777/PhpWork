<? if (count($this->array_tags)): ?>
    <h2 class="_padding-for-h1">Список дескрипторов</h2>
    <ul id="list-tags">
        <? foreach ($this->array_tags as $key => $value): ?>
            <li>
                <div class="block-list-tags"><?= $value['name'] ?></div>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>