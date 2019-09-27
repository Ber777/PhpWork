<?php

class Tag
{
    var $id = 0;
    var $name = '';
    var $obj_id = 0;//id объекта
    var $link;
    var $tag_list = array(); //массив тегов для добавления объекту (хранит имена тегов)
    var $search_name = ''; //название для поиска
    var $place_id; //id местооложения дескрипторов

    public function __construct()
    {
    }

    public function add_teg() //добавление нового тега в БД
    {
        $this->name = stripslashes($this->name);
        $query = pg_query($this->link, "SELECT * FROM nir.add_newtag('$this->name') ");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Добавление нового дескриптора';
        $inputs = 'Название: ' . $this->name;
        if ($result[0] > 0)
            $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает при успехе id тега, при неудаче 0
    }

    public function get_teg_byID() //получение имени тега по id
    {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.get_teg_by_id($this->id) ");
        $result = pg_fetch_array($query);
        return $result; //возвращает имя тега
    }

    //Удаление тега у объекта
    public function drop_teg()
    {
        $this->id = (int)$this->id;
        $this->obj_id = (int)$this->obj_id;
        $query = pg_query($this->link, "SELECT * FROM nir.droptag($this->id, $this->obj_id)");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Удаление дескриптора у объекта';
        $inputs = 'ID дескриптора: ' . $this->id . ', ID объекта: ' . $this->obj_id;
        if ($result[0] == 1)
            $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //при успешном удалении возвращает 1, в противном случае - 0
    }

    //Получение списка всех тегов
    public function get_all_tegs()
    {
        $query = pg_query($this->link, "SELECT * FROM nir.gettaglist()");
        $tegs = array();
        while ($data = pg_fetch_array($query)) {
            $tegs[] = array("id" => $data['id'], "name" => $data['name']);
        }
        return $tegs; //возвращает массив тегов (id, name)
    }

    //получение тегов объекта
    public function get_tags_obj()
    {
        $this->obj_id = (int)$this->obj_id;
        $query = pg_query($this->link, "SELECT * FROM nir.get_tags_obj($this->obj_id)");
        $tags = array();
        while ($data = pg_fetch_array($query)) {
            $tags[] = array("id" => $data['tag_id'], "name" => $data['tag_name']);
        }
        return $tags; //возвращает массив тегов (id, name)
    }

    //Добавление тегов объекту (для вызова нужно сформировать массив имен тегов и присвоить его в переменную класса tag_list)
    public function add_tags_to_obj()
    {
        $this->obj_id = (int)$this->obj_id;
        $query = pg_query($this->link, "SELECT * FROM nir.add_tags_to_obj($this->obj_id, $this->tag_list)");
        $result = pg_fetch_array($query);
        return $result; //возвращает число тегов объекта
    }

    //поиск тегов по названию
    public function search_tags()
    {
        $this->search_name = trim(stripslashes(strip_tags($this->search_name)));
        $query = pg_query($this->link, "SELECT * FROM nir.search_tags_by_name('$this->search_name')");
        $tags = array();
        while ($data = pg_fetch_array($query)) {
            $tags[] = array("id" => $data['id'], "name" => $data['name']);
        }
        return $tags;
    }

    public function GetListTagsIn()
    {
        $query = pg_query($this->link, "SELECT distinct tag_id, tag_name, upper(tag_name) 
                                    as uname FROM nir.tags_view where obj_id in 
                                    (SELECT o_id from nir.find_doc_by_tag($this->place_id, ARRAY[]::text[])) 
                                    order by upper(tag_name)");
        while ($result = pg_fetch_array($query)) {
            $list[] = array("id" => $result['tag_id'], "name" => $result['tag_name']);
        }
        return $list;
    }

    public function EditTag()
    {
        $query = pg_query($this->link, "SELECT nir.changename_tag($this->id, '$this->name')");
        $result = pg_fetch_array($query);
        return $result;
    }

    public function DropTag()
    {
        $query = pg_query($this->link, "SELECT nir.drop_tag($this->id)");
        $result = pg_fetch_array($query);
        return $result;
    }

    public function AddTag()
    {
        $query = pg_query($this->link, "select nir.addtag('$this->name')");
        return $query;
    }

    


}
