<table id="error_info">

    <? if (count($this->array_success_form)): ?>
        <tr class="main_row">
            <td>Название операции</td>
            <td>Описание ошибки</td>
        </tr>
        <? foreach ($this->array_success_form as $key => $value): ?>
            <tr style="background-color: #5ed26d">
                <td><?= $key ?></td>
                <td><?= $value ?></td>
            </tr>
        <? endforeach; ?>
    <? endif; ?>
    <tr class="main_row">
        <td>Название ошибки</td>
        <td>Описание ошибки</td>
    </tr>
    <? foreach ($this->array_errors_form as $key => $value): ?>
        <tr style="background-color: #d25e5e">
            <td><?= $key ?></td>
            <td><?= $value ?></td>
        </tr>
    <? endforeach ?>
</table>