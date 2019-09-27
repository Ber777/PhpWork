<h5 class="_underline"></h5>
<? if (count($this->array_documents[$data]['attributes'])): ?>
    <? foreach ($this->array_documents[$data]['attributes'] as $item): ?>
        <p><?=$item['name'] ?> :: <?=$item['value'] ?></p>
    <? endforeach; ?>
<? else: ?>
<? endif; ?>
