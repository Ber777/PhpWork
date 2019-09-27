<ul class="list-float-none" id="list-file-in">
    <li></li>
    <? if(count($this->array_documents)): ?>
        <h3 class="_text-align-left">Файлы имеющиеся в рубрике</h3>
        <? foreach ($this->array_documents as $key => $item): ?>
            <li class="standart-button">
                <a class="hover-underline" href="/document/passport/<?=$item['id'] ?>"><?=$item['name'] ?></a>
                <? $this->AdminPanelObject('document', $key) ?>
                <p>
                    <? $this->ListSecondaryObjects('attributes', $key) ?>
                    <? $this->ListSecondaryObjects('tags', $key) ?>
                </p>
            </li>
        <? endforeach; ?>
    <? else: ?>
        <h2 class="_padding-for-h1">Файлы в рубрике отсутствуют</h2>
    <? endif; ?>
</ul>
