<ul class="list-float-none" id="list-file-in">
    <li></li>
    <? if(count($this->array_documents)): ?>
        <h3 class="_text-align-left">Документы удовлетворяющие запросу:</h3>
        <? foreach ($this->array_documents as $key => $item): ?>
            <li class="standart-button">
                <a class="hover-underline" href="/document/passport/<?=$item['id'] ?>"><?=$item['name'] ?></a>
                <? $this->AdminPanelObject('document', $key) ?>
                <? $this->ListSecondaryObjects('attributes', $key) ?>
                <? $this->ListSecondaryObjects('tags', $key) ?>
                <? $this->BlockNavigationChain('default', $key) ?>
            </li>
        <? endforeach; ?>
    <? else: ?>
        <h2 class="_padding-for-h1">Ничего не найдено</h2>
    <? endif; ?>
</ul>
