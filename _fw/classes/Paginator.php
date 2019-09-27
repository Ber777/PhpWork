<?php

class Paginator {
    private $objects; // массив объектов
    private $objects_count; // количество объектов
    private $per_page; // количество объектов на странице
    private $current_page; // номер текущей страницы
    private $url_page_param; // название URL параметра, отвечающего за номер страницы

    public function __construct($objects, $per_page, $url_param = 'page') {
        $this->objects = $objects;
        $this->objects_count = count($objects);
        $this->per_page = $per_page;
        $this->url_page_param = $url_param;
        if($per_page > $this->objects_count) $this->per_page = $this->objects_count;
    }

    public function get_link($page_number) {
        $url = $_SERVER['REQUEST_URI'];
        $query = explode('?', $url);
        parse_str($query[1], $data);
        $data[$this->url_page_param] = $page_number;
        $url = $query[0] . '?' . http_build_query($data);
        return $url;
    }

    public function get_url_page_param() {
        return $this->url_page_param;
    }

    public function page($number) {
        $number = $this->validate_number($number);
        $this->current_page = $number;
        $bottom = ($number - 1) * $this->per_page;
        return array_slice($this->objects, $bottom, $this->per_page);
    }

    public function current_page() {
        return $this->current_page;
    }

    public function prev_page() {
        return $this->current_page > 1 ? $this->current_page - 1 : 1; 
    }                            

    public function next_page() {
        return $this->current_page != $this->num_pages() ? $this->current_page + 1 : $this->current_page; 
    }

    public function first_page() {
        return 1;
    }

    public function last_page() {
        return $this->num_pages();
    }

    public function num_pages() {
        if($this->objects_count == 0) return 0;
        return ceil($this->objects_count / $this->per_page);
    }

    private function validate_number($number) {
        if(!is_int($number) || $number < 1) return 1; 
        if($number > $this->num_pages()) return $this->num_pages();
        return $number;
    }
}
