
<? if ((count($this->array_catalogs) + count($this->array_documents))): ?>
    <h2>Буфер обмена</h2>
    <form style="width: 700px" id="copy-from-buffer">
        <input type="hidden" name="object" value="<?=$this->name_object ?>">
        <input type="hidden" name="id_prent" value="<?=$this->id_parent ?>">
        <table id="list-buffer">
            <thead>
            <tr>
                <td class="buf-title-obj" colspan="2">Название</td>
                <td class="buf-link-obj">Ссылка на объект</td>
                <td class="buf-del-obj">Удалить из буфера</td>
            </tr>
            </thead>
            <tbody>
            <? if (count($this->array_catalogs)): ?>
                <? foreach ($this->array_catalogs as $catalog): ?>
                    <tr align="left">
                        <td width="50px">
                            <input type="checkbox"
                                   id="cat<?=$catalog['id'] ?>"
                                   name="cat<?=$catalog['id'] ?>"
                                   value="<?=$catalog['id'] ?>"
                                   class="css-checkbox"
                            >
                            <label for="cat<?=$catalog['id'] ?>" class="css-label"></label>
                        </td>
                        <td>
                            <img src="/images/folder-small.png">
                            <div class="label-middle">
                                <label for="cat<?=$catalog['id'] ?>"><?=$catalog['name'] ?></label>
                            </div>
                        </td>
                        <td align="center">
                            <a class="click-button" target="_blank" href="/<?=$catalog['name_type_first_parent'] ?>/view/<?=$catalog['id'] ?>/">
                                <img width="20px" src="/images/link.png">
                            </a>
                        </td>
                        <td align="center">
                            <a class="click-button delete-from-buffer" idd="<?=$catalog['id'] ?>">
                                <img src="/images/close.png">
                            </a>
                        </td>
                    </tr>
                <? endforeach; ?>
            <? endif ?>
            <? if (count($this->array_documents)): ?>
                <? foreach ($this->array_documents as $document): ?>
                    <tr align="left">
                        <td width="50px">
                            <input type="checkbox"
                                   id="doc<?=$document['id'] ?>"
                                   name="doc<?=$document['id'] ?>"
                                   value="<?=$document['id'] ?>"
                                   class="css-checkbox"
                            >
                            <label for="doc<?=$document['id'] ?>" class="css-label"></label>
                        </td>
                        <td>
                            <img src="/images/file-small.png">
                            <div class="label-middle">
                                <label for="doc<?=$document['id'] ?>"><?=$document['name'] ?></label>
                            </div>
                        </td>
                        <td align="center">
                            <a class="click-button" target="_blank" href="/document/passport/<?=$document['id'] ?>/">
                                <img width="20px" src="/images/link.png">
                            </a>
                        </td>
                        <td align="center">
                            <a class="click-button delete-from-buffer" idd="<?=$document['id'] ?>">
                                <img src="/images/close.png">
                            </a>
                        </td>
                    </tr>
                <? endforeach; ?>
            <? endif ?>
            </tbody>
        </table>
        <ol class="display_errors" style="display: none; padding: 10px">
            <p><b>При копировании были замечены следующие ошибки: (все остальные документы были успешно скопированы)</b></p>
        </ol>
        <p style="margin-top: 10px">
            <input class="click-button" type="submit" value="Вставить">
            <a style="color: red;" class="click-button clear-buffer">Очистить буфер</a>
            <a style="color: red;" class="click-button cancel-popup">Закрыть буфер</a>
        </p>
    </form>
<? else: ?>
    <h1>Буфер обмена пуст</h1>
    <p><a style="color: red;" class="click-button cancel-popup">Закрыть буфер</a></p>
<? endif; ?>
