<?php

// функция, упрощающая пагинацию списка объектов
function paginate($paginator, $controller) {
    $page_num = intval($controller->getURL($paginator->get_url_page_param(), false));
    return $paginator->page($page_num);
}

function _fw_print_html_tag($tag, $text) {
    echo "<$tag>";
    print_r($text);
    echo "</$tag>";
}

function _fw_json_encode($value) {
    $result = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return $result;
}

function _fw_json_decode_assoc($value) {
    $result = json_decode($value, TRUE);
    return $result;
}

function _fw_convert_object_to_array($object) {
    if (is_object($object)) {
        $result_encode = _fw_json_encode($object);
        $result_decode = _fw_json_decode_assoc($result_encode);
        return $result_decode;
    } else {
        echo 'Не подается объект в функцию'. __FUNCTION__;
        return FALSE;
    }
}

// перевод типов атрибутов (число - текст, текст - число,  число - русс)
function _fw_translate_type_attribute ($value, $to) {
    if ($to == 'string') {
        if ($value == 1) $type = 'number';
        else if ($value == 2) $type = 'text';
        else if ($value == 3) $type = 'date';
        return $type;
    }
    elseif ($to == 'int') {
        if ($value == 'number') $type = 1;
        else if ($value == 'text') $type = 2;
        else if ($value == 'date') $type = 3;
        return $type;
    }
    if ($to == 'rus') {
        if ($value == 1) $type = 'число';
        else if ($value == 2) $type = 'текст';
        else if ($value == 3) $type = 'дата';
        return $type;
    }
}

function _fw_translate_type_object ($value, $to) {
    $result = '';
    if ($to == 'eng') {
        if ($value == 1) $result = 'mapknowledge';
        if ($value == 13) $result = 'database';
    }
    return $result;
}
