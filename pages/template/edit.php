<?php

$helper->Title('default', 'Редактирование шаблона');

$helper->drawBlock('form-addedit', 'step_1');
$helper->drawBlock('form-addedit', 'step_2');
$helper->drawBlock('form-addedit', 'step_3');
$helper->drawBlock('form-addedit', $helper->step_4);