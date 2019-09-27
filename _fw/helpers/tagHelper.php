<?php

class tagHelper extends Helper {

    var $array_sort_alphabet;
    var $place; // местоположение тегов, определяется через форму сортировки по местонахождению
    var $sort_by; // буква по которой сортируются дескрипторы
    
    function __construct() {
    }

    public function FormAddTag() {
        $this->drawBlock('forms', 'add_tag');
    }

    public function SortTags() {
        $this->drawBlock('forms', 'sort_by_place');
    }

    public function ajaxBlockListTags() {
        echo $this->ListObjects('tags');
    }

    public function ListAlphabetFromTags() {
        if (count($this->array_tags)) {
            foreach ($this->array_tags as $key => $item) {
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

    public function ListTags() {
        if (count($this->array_tags)) {
            foreach ($this->array_tags as $key => $item) {
                $first_symbol = mb_substr($item['name'], 0, 1, 'utf-8');
                $index_alphabet = mb_strtoupper($first_symbol, 'utf-8');
                if ($index_alphabet != $this->sort_by) {
                    unset($this->array_tags[$key]);
                }
            }
        }
        $this->ListObjects('tags');
    }

    public function HideFormSearch() {
        $this->BlockForm('hide_form_search_by_tag');
    }


}