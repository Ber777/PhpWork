<h4 class="title-step">Шаг 4. Заполнение данных</h4>
<form action="" method="POST" id="main-form" enctype="multipart/form-data" class="block-step _text-align-left _display-block" ajax="<?=$this->url_ajax_handler ?>" id_object="<?=$this->id_for_ajax ?>">
    <p class="block-error _display_none">
    </p>
    <ul class="list-float-none" id="list-fields">
        <li><label for="">Название шаблона: </label><input itype="2" name="namef" class="standart-input" type="text" placeholder="введите имя шаблона" value="<?=$this->name_template ?>" required></li>
        <? if (count($this->array_attributes_object)): ?>
            <? foreach ($this->array_attributes_object as $item): ?>
                <li num-li="<?=$item['id'] ?>" option-data="attribute">
                    <label for=""><?=$item['name'] ?> [<?=$item['type_rus'] ?>]</label>
                    <input
                        itype="<?=$item['itype'] ?>"
                        name="<?=$item['name'] ?>"
                        class="<?=$item['datepicker'] ?> standart-input"
                        type="<?=$item['type'] ?>"
                        placeholder="Введите <?=$item['name'] ?>"
                        value="<?=$item['value'] ?>"
                        />
                    <a style="position: absolute;" class="delete-li-field pointer">Удалить поле</a>
                </li>
            <? endforeach; ?>
        <? endif; ?>
    </ul>
    <ul class="list-float-left  list-tegs _inline-block" id="list-tegs">
        <h4 style="text-align: left;">Дескрипторы:</h4>
        <? if (count($this->array_tags_object)): ?>
            <? foreach ($this->array_tags_object as $item): ?>
                <li class="block-list-tags" idtag="<?= $item['id'] ?>" option-data="tag"><?= $item['name'] ?><a class="delete-li-field pointer">×</a></li>
            <? endforeach; ?>
            <p class="_padding-for-h1"></p>
        <? else: ?>
            <p class="_padding-for-h1">Отсутствуют</p>
        <? endif; ?>
    </ul>
    <div class="_center-in-div" style="margin-bottom: 10px;">
        <p class="_padding-for-h1">
            <? if ($this->path_file_template_doc): ?>
                Ссылка на файл: <a target="_blank" class="_underline name-file" href="<?=$this->path_file_template_doc ?>"><?=$this->path_file_template_doc ?></a>
            <? endif; ?>
                <input delete="<?=$this->path_file_template_doc ?>" type="file" class="click-button" its="docs" name="doc" id="doc">
            </p>
    </div>
    <p class="_center-in-div">
        <? $this->SubmitMainForm($this->type_submit); ?>
    </p>
</form>