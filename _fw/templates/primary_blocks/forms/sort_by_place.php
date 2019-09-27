<form method="GET" >
    <p class="_text-align-left">Выберите местоположение:
        <select class="standart-input" name="place" size="1">
            <option selected="" style="font-weight: bolder;" value="0">Везде</option>
            <? if (count($this->array_mk)): ?>
            <optgroup label="Карты знаний">
                <? foreach ($this->array_mk as $key => $value): ?>
                    <? if ($value['id'] == $this->place): ?>
                        <option selected value="<?=$value['id'] ?>"><?=$value['name'] ?></option>
                    <? else: ?>
                        <option value="<?=$value['id'] ?>"><?=$value['name'] ?></option>
                    <? endif; ?>
                <? endforeach; ?>
            </optgroup>
            <? endif; ?>
            <? if (count($this->array_db)): ?>
            <optgroup label="Базы данных">
                <? foreach ($this->array_db as $key => $value): ?>
                    <? if ($value['id'] == $this->place): ?>
                        <option selected value="<?=$value['id'] ?>"><?=$value['name'] ?></option>
                    <? else: ?>
                        <option value="<?=$value['id'] ?>"><?=$value['name'] ?></option>
                    <? endif; ?>
                <? endforeach; ?>
            </optgroup>
            <? endif; ?>
        </select>
        <input id="select-place" class="click-button" type="submit" value="Показать">
    </p>
</form>