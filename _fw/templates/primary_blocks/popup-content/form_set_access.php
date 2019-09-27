<div style="width: 80%; margin: 0 auto;">
    <h1>Назначение прав для "<?=$this->name_current_object ?>"</h1>
    <h4>Владелец: <?=$this->owner_object['name'] ?></h4>
    <form action="" method="POST" id="add_access_window">
        <div style="text-align: left">
            <select id="name" class="standart-input">
                <? if (count($this->list_users_without_roles)): ?>
                    <option hidden selected="" value=""></option>
                    <? foreach ($this->list_users as $id_user => $name_user): ?>
                        <? if ($id_user != $this->user_info['id'] || $id_user != $this->owner_object['id']): ?>
                            <option <? echo (isset($this->list_users_with_roles[$id_user])) ? 'hidden' : '' ?> data-max-role="<?=$this->main_role_user[$id_user] ?>" value="<?=$id_user ?>"><?=$name_user ?></option>
                        <? endif; ?>
                    <? endforeach; ?>
                <? endif; ?>
            </select>
            <p style="margin: 0" class="click-button pointer_add_new" data-count="<?=count($this->array_roles_object) ?>">Добавить пользователя</p>
        </div>
        <table class="data" id="table">
            <thead>
            <tr>
                <th></th>
                <? foreach ($this->array_roles_object as $key => $role) : ?>
                    <th style="min-width: 200px;">
                        <p><?=$role[0] ?></p>
                        <p id="desc_access"><?=$role[1] ?></p>
                    </th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody id="name_user">
            <?php foreach ($this->list_users_with_roles as $id_user => $bit_mask_user) : ?>
                <? if ($id_user != $this->user_info['id'] || $id_user != $this->owner_object['id']): ?>
                    <tr id='<?=$id_user ?>'>
                    <td style="min-width: 150px;">
                        <?=$this->list_users[$id_user] ?>
                    </td>
                    <?php foreach ($this->array_roles_object as $key_role => $arr_description_role) : ?>
                        <?php foreach ($this->list_users_with_roles as $id_user2 => $bit_mask) : ?>
                            <?php if ($id_user == $id_user2) : ?>
                                <?php if ($bit_mask >= $key_role) :?>
                                    <?php if ($bit_mask == 11111) :?>
                                        <td align ="center"><input class="flag"
                                                                   mask="<?=$key_role ?>"
                                                                   disabled=""
                                                                   nameUser="<?=$bit_mask_user ?>"
                                                                   checked=""
                                                                   user="<?=$id_user ?>"
                                                                   access="<?=$key_role ?>"
                                                                   type="checkbox"
                                                                   id="way[<?=$id_user ?>][<?=$key_role ?>]"
                                                                   name="way[<?=$id_user ?>][<?=$key_role ?>]"/>
                                        </td>
                                    <?php else : ?>
                                        <td align ="center"><input class="flag"
                                                                   mask="<?=$key_role ?>"
                                                                   nameUser="<?=$bit_mask_user ?>"
                                                                   checked=""
                                                                   user="<?=$id_user ?>"
                                                                   access="<?=$key_role ?>"
                                                                   type="checkbox"
                                                                   id="way[<?=$id_user ?>][<?=$key_role ?>]"
                                                                   name="way[<?=$id_user ?>][<?=$key_role ?>]"/>
                                        </td>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <td align ="center"><input class="flag"
                                            <? if ($key_role > $this->main_role_user[$id_user2]): ?>
                                                disabled
                                            <? endif; ?>
                                                               mask="<?=$key_role ?>"
                                                               nameUser="<?=$bit_mask_user ?>"
                                                               user="<?=$id_user ?>"
                                                               access="<?=$key_role ?>"
                                                               type="checkbox"
                                                               id="way[<?=$id_user ?>][<?=$key_role ?>]"
                                                               name="way[<?=$id_user ?>][<?=$key_role ?>]"/>
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <td>
                        <? if ($this->user_info['is_owner'] == 1): ?>
                            <span class="pointer1 click-button" data-nameUser="<?=$id_user ?>">удалить</span>
                        <? endif; ?>
                    </td>
                <? endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" name="id_obj" value="<?=$this->id_current_object ?>"/>
        <p>
            <button style="color: green" class="click-button" name="btnroleaccess">Отправить</button>
            <a style="color: red; padding: 3px 25px; font-size: 14px;" class="click-button cancel-popup">Отмена</a>
        </p>
        <p id="addrightmsg"></p>
    </form>
</div>
<?// _fw_print_html_tag('pre', $this) ?>



