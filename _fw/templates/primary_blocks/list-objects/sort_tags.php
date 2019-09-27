<? if (count($this->array_sort_alphabet)): ?>
<ul class="list-letters">
    <? if (count($this->array_sort_alphabet['numbers'])) : ?>
    <p class="padding-for-div">Цифры</p>
        <? foreach ($this->array_sort_alphabet['numbers'] as $key => $value): ?>
            <li><a href="?place=<?=$this->place ?>&filter=<?=$key ?>" class="symbol click-button"><?=$key ?></a></li>
        <? endforeach; ?>
    <? endif; ?>
    <? if (count($this->array_sort_alphabet['english'])) : ?>
        <p class="padding-for-div">Англ.</p>
        <? foreach ($this->array_sort_alphabet['english'] as $key => $value): ?>
            <li><a href="?place=<?=$this->place ?>&filter=<?=$key ?>" class="symbol click-button"><?=$key ?></a></li>
        <? endforeach; ?>
    <? endif; ?>
    <? if (count($this->array_sort_alphabet['russian'])) : ?>
        <p class="padding-for-div">Русс.</p>
        <? foreach ($this->array_sort_alphabet['russian'] as $key => $value): ?>
            <li><a href="?place=<?=$this->place ?>&filter=<?=$key ?>" class="symbol click-button"><?=$key ?></a></li>
        <? endforeach; ?>
    <? endif; ?>
</ul>
<? endif; ?>