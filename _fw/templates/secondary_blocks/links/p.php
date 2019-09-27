<p>
    <a
        <? if ($data['href']): ?>
            href="<?=$data['href'] ?>"
        <? endif ?>
        class="<?=$data['class'] ?>">

        <?=$data['text'] ?>

    </a>
</p>