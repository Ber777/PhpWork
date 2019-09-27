<div class="page-content center-in-div">
        <h2><?=$data[1] ?></h2>
        <? //var_dump($resultSearch) ?>
        <ul style="display: <?=$data[2] ?>;" class="list-float-none padding-for-div" id="list-file-in-biblio">
            <li></li>
<?
           // $itemDocument = pg_fetch_assoc($query);
           // while ( $itemDocument )                            
           ?>
                    <? foreach ($data[0] as $itemDocument):             ?>
                <li class="standart-button">
                    <a class="hover-underline"  href="/document/passport/<?=$itemDocument['id'] ?>" target="_balnk"><?='  '.$itemDocument['name'] ?></a>
                   
                    <?php foreach ($itemDocument['attr_list'] as $itemAttribute): ?>
                        <p><?= $itemAttribute['name'].' : '.$itemAttribute['value']; ?></p>
                    <?php endforeach; ?>
                    <ul class="list-tegs list-float-left">
                 
                        <?php foreach($itemDocument['tags_list'] as $itemTag): ?>
                            <li>
                                <div class="block-list-tags"><?php echo $itemTag['name'] ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php // echo "Каталог: ".$itemDocument['catalog'] ?>
                </li>
                 <?php endforeach; ?>
            <?php 
              // $itemDocument = pg_fetch_assoc($query);
             ?>
        </ul>
</div>            