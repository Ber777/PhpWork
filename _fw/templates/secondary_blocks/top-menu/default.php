<nav role="navigation">
    <ul>
        <?php foreach ($data['ITEM_MENU'] as $key => $itemLinks): ?>
            <li>
                <a class="<?=$itemLinks['class'] ?>" href="<?=$itemLinks['link'] ?>"><?=$itemLinks['name']?></a>
                <?php if (isset($data['SUB_ITEMS'][$key])): ?>
                    <ul>
                        <?php foreach ($data['SUB_ITEMS'][$key] as $key2 => $itemSubLinks): ?>
                            <li>
                                <a class="<?=$itemSubLinks['class'] ?>"
                                    <?php if (isset($itemSubLinks['link'])): ?>
                                        href="<?=$itemSubLinks['link'] ?>"
                                    <?php endif; ?>
                                >
                                    <?=$itemSubLinks['name']?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <li>&nbsp;&nbsp;<a href = '/exitSession.php' class="click-button"><img src="/images/logout.png"></a></li>
        <!--        <li><p>&nbsp;</p><p align="right" color="#00FF00;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "principal Kerberos: ".$_SERVER['REMOTE_USER']; ?></p></li> -->
        <!--<li><p>&nbsp;</p><p align="right" color="#00FF00;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php #echo $curuser->name;  ?></p></li>-->
    </ul>
</nav>

