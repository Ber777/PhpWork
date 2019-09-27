<? if (count($this->array_attributes)): ?>
    <h2 class="_padding-for-h1">Таблица атрибутов</h2>
    <table id="table-attributes">
        <tbody>
        <tr style="background-color: #bbb9b9">
            <td>Название атрибута</td>
            <td>Значение атрибута</td>
        </tr>
        <? foreach ($this->array_attributes as $key => $value): ?>
            <tr>
                <td><?= $value['name'] ?></td>
                <td><?= $value['value'] ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
<? endif; ?>