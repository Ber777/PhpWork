<table id="error_info">
    <tr class="main_row">
        <td>Ключ ошибки</td>
        <td>Описание ошибки</td>
    </tr>
    <?php foreach ($data as $key => $value): ?>
        <tr>
            <td><?=$key ?></td>
            <td><?=$value ?></td>
        </tr>
    <?php endforeach ?>
</table>
