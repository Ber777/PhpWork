<div id="search">
    <form id="form-search" method="post" action="/search/result/">
        <input class="standart-input" id="input-tags-for-search" type="text" name="input-tag" placeholder="Введите теги для поиска"
               autocomplete="off"><label style="display: none">Добавить</label>
        <ul id="list-result-search-tags" class="list-tegs list-float-left">
        </ul>
        <p>
            <input required="" type="radio" value="" name="search-radio" id="search-in-all" checked=""><label
                for="search-in-all"><?=$this->form_search['search-in-all'] ?></label>
            <? if ($this->id_main_parent_object): ?>
                <input required="" type="radio" value="<?= $this->id_main_parent_object ?>" name="search-radio"
                       id="search-in-first-parent"><label for="search-in-first-parent"><?=$this->form_search['search-in-first-parent'] ?></label>
            <? endif; ?>
            <? if ($this->id_current_object != $this->id_main_parent_object): ?>
                <input required="" type="radio" value="<?= $this->id_current_object ?>" name="search-radio"
                       id="search-in-current"><label for="search-in-current"><?=$this->form_search['search-in-current'] ?></label>
            <? endif; ?>
        </p>


        <ul id="list-search-tags" class="list-tags">
            <?  if (property_exists(get_class($this), "search_result_tags") && count($this->search_result_tags)>0): ?>
                <p style="padding: 10px;"><b>Выбранные дескрипторы для поиска:</b></p>
                <? foreach ($this->search_result_tags as $key => $item): ?>
                    <li class="block-list-tags"><?=$item ?><a class="delete-li-field delete-from-search pointer">×</a></li>
                <? endforeach; ?>
            <? endif; ?>
        </ul>
        <? if (property_exists(get_class($this), "place_name_search") && $this->place_name_search): ?>
            <p style="padding: 10px;"><b>Текущее местоположение поиска: </b><?=$this->place_name_search ?></p>
        <? endif; ?> 

        <p>
            <input name="list-tags" type="hidden" id="result-list-tags" value="<?=property_exists(get_class($this), 
'search_result_tags_input') ? $this->search_result_tags_input: '' ?>">
            <input id="type-search" type="hidden" name="type-search" value="quick">
            <input id="start-search" class="click-button" type="submit" value="Найти">
            <a class="_underline" href="/bigsearch/">Перейти к расширенному поиску</a>
        </p>
    </form>
</div>


