<? if (count($this->array_sort_alphabet)): ?>
    <ul class="list-tags _padding-for-h1">
        <? foreach ($this->array_tags as $key => $tag): ?>
            <li>
                <div class="block-list-tags">
                    <? $this->AdminPanelObject('tag', $key); ?>
                    <p><?= $tag['name']; ?></p>
                </div>
            </li>
        <? endforeach; ?>
    </ul>
<? else: ?>
    <h3>Дескрипторы отсутствуют</h3>
<? endif; ?>