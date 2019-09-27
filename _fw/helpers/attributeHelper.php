<?php

class attributeHelper extends Helper {

    var $array_sort_alphabet;
    var $place; // местоположение тегов, определяется через форму сортировки по местонахождению
    var $sort_by; // буква по которой сортируются дескрипторы
    var $attribute_info; // информация о атрибуте

    public function FormAddAttribute() {
        $this->BlockForm('add_attribute');
    }

    public function SortAttributes() {
        $this->drawBlock('forms', 'sort_by_place');
    }

    public function ListAlphabetFromAttributes() {
        if (count($this->array_attributes)) {
            foreach ($this->array_attributes as $key => $item) {
                $first_symbol = mb_substr($item['name'], 0, 1, 'utf-8');
                $index_alphabet = mb_strtoupper($first_symbol, 'utf-8');
                mb_regex_encoding('utf-8');
                if (mb_ereg("[a-zA-Z]", $index_alphabet)) {
                    $this->array_sort_alphabet['english'][$index_alphabet][$key] = $item;
                }
                elseif (mb_ereg( "[а-яА-ЯЁё]", $index_alphabet)) {
                    $this->array_sort_alphabet['russian'][$index_alphabet][$key] = $item;
                }
                elseif (mb_ereg("[0-9]", $index_alphabet)) {
                    $this->array_sort_alphabet['numbers'][$index_alphabet][$key] = $item;
                }
            }
        }
        $this->ListObjects('sort_tags');
    }
    
    public function ListAttributes() {
        if (count($this->array_attributes)) {
            foreach ($this->array_attributes as $key => $item) {
                $first_symbol = mb_substr($item['name'], 0, 1, 'utf-8');
                $index_alphabet = mb_strtoupper($first_symbol, 'utf-8');
                if ($index_alphabet != $this->sort_by) {
                    unset($this->array_attributes[$key]);
                }
            }
        }
        $this->ListObjects('attributes');
    }

    public function HideFormSearch() {
        $this->BlockForm('hide_form_search_by_attr');
    }
    
    public function FormEditAttribute() {
        $this->PopUpContent('form_edit_attribute');
    }

}