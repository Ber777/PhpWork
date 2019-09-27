
<? if (count($this->array_documents[$data]['tags'])): ?>
<ul class="list-tags list-float-left">
    <? foreach ($this->array_documents[$data]['tags'] as $item): ?>
    <li>
        <div class="block-list-tags"><?=$item['name'] ?></div>
    </li>
    <? endforeach; ?>
</ul>
<? else: ?>
<? endif; ?>