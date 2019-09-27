<h3 class="_padding-for-h1"><?=$this->title_block ?></h3>
<? if (count($this->array_templates_for_print)): ?>
<ul class="list-templates">
    <? foreach ($this->array_templates_for_print as $key => $value): ?>
    <li>
        <div class="block-list-items">
            <? $this->AdminPanelObject($this->admin_panel_object, $key) ?>
            <p><?=$value['name'] ?></p>
        </div>
    </li>
    <? endforeach; ?>
</ul>
<? else: ?>
    <p>Шаблоны отсутсвуют</p>
<? endif; ?>
