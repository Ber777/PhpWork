
<table class="data" id="table-list-users">
    <thead>
    <tr>
        <td>Имя пользователя</td>
        <td>Роль в системе</td>
    </tr>
    </thead>
    <tbody>
    <? foreach ($this->array_users as $id_user => $name_user): ?>
        <tr>
            <td><?=$name_user ?></td>
            <td>
                <? $count = 0; ?>
                <? foreach ($this->array_roles[$id_user] as $key => $name_role): ?>
                    <? $count++; ?>
                    <?=$name_role?>
                    <? if ((count($this->array_roles[$id_user]) > 1) AND ($count != count($this->array_roles[$id_user]))): ?>
                        <span>,</span>
                    <? endif; ?>
                <? endforeach; ?>
            </td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>
<a style="color: red; padding: 3px 25px; font-size: 14px;" class="click-button cancel-popup">Закрыть</a>

