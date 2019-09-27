<div id="nav-chain">
    <h4>Местоположение документа:</h4>
    <ul>
        <? foreach ($data['parent_path'] as $item): ?>
        <li><a href="/<?=$data['main_parent'] ?>/view/<?=$item['id'] ?>"><?=$item['name']?></a></li>
        <? endforeach; ?>
    </ul>
</div>