<div id="comments">
    <? if (count($this->array_messages)): ?>
        <h3>Комментарии текущей карты знаний</h3>
        <ul id="comments-mapknowledge" class="list-float-none">
            <? foreach ($this->array_messages as $key => $value): ?>
                <li class="comment">
                    <div>
                        <p>
                            <span>Автор: <?=$value['user_name'] ?></span>
                        </p>
                        <p>
                            <span> Дата: <?=$value['date'] ?></span>
                        </p>
                    </div>
                    <div class="field-text">
                        <? $this->AdminPanelObject('message', $key) ?>
                        <p><?=$value['txt'] ?></p>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? else: ?>
        <h3 class="_padding-for-h1">Комментарии отсутствуют</h3>
    <? endif; ?>
    <h4 style="padding: 15px 0;">Написать комментарий к карте знаний</h4>
    <form>
        <textarea id="message"></textarea>
        <input id="idkz" type="hidden" disabled="" value="<?=$this->id_main_parent_object ?>">
        <input id="nameuser" type="hidden" disabled="" value="<?=$this->user_info['name_user'] ?>">
        <p>
            <input class="click-button" type="button" id="button-add-message" value="отправить сообщение ">
        </p>
    </form>
</div>
