<? if ($data['href']): ?>
    <div style="border-bottom: 1px solid darkgray; padding: 20px">
        <a target="_blank" href="<?= $data['href'] ?>" class="<?= $data['class'] ?>"><?= $data['text'] ?></a>
    </div>
<? endif; ?>