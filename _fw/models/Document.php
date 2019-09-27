<?php

class Document
{
    var $name = ''; //название
    var $id = 0;
    var $tegs = array(); //массив тегов (названия)
    var $attributes = array(); //массив атрибутов (тип, название, значение)
    var $parent_id = 0; //id каталога, в котором лежит документ
    var $link;
    var $file_name = '';

    public function __construct()
    {
    }

    public function addTemplate()
    {
        $this->name = trim(stripslashes(strip_tags($this->name)));
        $tags_row = "ARRAY[";
        $i = 0;
        foreach ($this->tegs as $t) {
            if ($i == 0) {
                $tags_row .= "'" . $t . "'";
                $i = 1;
            } else {
                $tags_row .= " ,'" . $t . "'";
            }
        }
        $tags_row .= "]::text[]";

        $i = 0;
        $attrs_row = "ARRAY[";
        foreach ($this->attributes as $attr) {
            if ($i == 0) {
                $attrs_row .= "cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
                $i = 1;
            } else {
                $attrs_row .= ", cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
            }
        }
        $attrs_row .= "]::nir.atrtype[]";
        $query = pg_query($this->link, "SELECT * FROM nir.add_template_doc('$this->name', " . $tags_row . ", " . $attrs_row . ")"); //в атрибуты подавать без значений (значения - пустые строки)
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Добавление шаблона документа';
        $inputs = 'Название: ' . $this->id;
        if ($result[0] > 0)
            $res = 'Успех';
        elseif ($result[0] == -1)
            $res = 'Ошибка. Шаблон с таким именем уже существует';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает id шаблона и -1 если такой есть уже
    }

    public function editTemplate() //не знаю, заработает или нет (нужно потестить)
    {
        $this->id = (int)$this->id; //id шаблона
        $this->name = trim(stripslashes(strip_tags($this->name)));
        $tags_row = "ARRAY[";
        $i = 0;
        foreach ($this->tegs as $t) {
            if ($i == 0) {
                $tags_row .= "'" . $t . "'";
                $i = 1;
            } else {
                $tags_row .= " ,'" . $t . "'";
            }
        }
        $tags_row .= "]::text[]";

        $i = 0;
        $attrs_row = "ARRAY[";
        foreach ($this->attributes as $attr) {
            if ($i == 0) {
                $attrs_row .= "cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
                $i = 1;
            } else {
                $attrs_row .= ", cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
            }
        }
        $attrs_row .= "]::nir.atrtype[]";
        $query = pg_query($this->link, "SELECT * FROM nir.edit_template($this->id, '$this->name', " . $tags_row . ", " . $attrs_row . ")"); //в атрибуты подавать без значений (значения - пустые строки)
        $result = pg_fetch_array($query);
        return $result;
    }

    public function delTemplate()
    {
        $this->id = (int)$this->id; //id шаблона
        $query = pg_query($this->link, "SELECT * FROM nir.drop_template($this->id)");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Удаление шаблона документа';
        $inputs = 'ID шаблона: ' . $this->id;
        if ($result[0] == 1)
            $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает 1 в случае успеха
    }

    //получение списка шаблонов
    public function get_all_doc_temp()
    {

        $query = pg_query($this->link, "SELECT * FROM nir.get_all_templates_doc()");
        $temps = array();
        while ($data = pg_fetch_array($query)) {
            $temps[] = array("id" => $data['id'], "name" => $data['name']);
        }
        return $temps;
    }

    public function get_my_doc_temp()
    {
        $query = pg_query($this->link, "SELECT * FROM nir.get_my_templates_doc()");
        $temps = array();
        while ($data = pg_fetch_array($query)) {
            $temps[] = array("id" => $data['id'], "name" => $data['name']);
        }
        return $temps;
    }

    //Добавление документа
    public function addDoc()
    {
        $this->parent_id = (int)$this->parent_id;
        $this->name = trim(stripslashes(strip_tags($this->name)));
        $tags_row = "ARRAY[";
        $i = 0;
        foreach ($this->tegs as $t) {
            if ($i == 0) {
                $tags_row .= "'" . $t . "'";
                $i = 1;
            } else {
                $tags_row .= " ,'" . $t . "'";
            }
        }
        $tags_row .= "]::text[]";

        $i = 0;
        $attrs_row = "ARRAY[";
        foreach ($this->attributes as $attr) {
            if ($i == 0) {
                $attrs_row .= "cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
                $i = 1;
            } else {
                $attrs_row .= ", cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
            }
        }
        $attrs_row .= "]::nir.atrtype[]";
        //echo "SELECT * FROM nir.adddoc('$this->name', $this->parent_id, ".$tags_row.", ".$attrs_row.")";
        $query = pg_query($this->link, "SELECT * FROM nir.adddoc('$this->name', $this->parent_id, " . $tags_row . ", " . $attrs_row . ")");
        $result = pg_fetch_array($query);

        //запись лога
        $action = 'Добавление документа';
	if(isset($this->obj_id))
 	{
        	$inputs = 'Название: ' . $this->name . ', ID рубрики: ' . $this->obj_id;
 		$res = 'Успех';
	}
       // if ($result[0] > 0)
       //     $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает id дока при успешном добавлении и -1, если такой док(с таким именем) есть уже
    }

    //Редактирование дока (пока не редактирует атрибуты)
    public function editDoc()
    {
        $this->id = (int)$this->id;
        $this->parent_id = (int)$this->parent_id;
        $this->name = trim(stripslashes(strip_tags($this->name)));
        $tags_row = "ARRAY[";
        $i = 0;
        foreach ($this->tegs as $t) {
            if ($i == 0) {
                $tags_row .= "'" . $t . "'";
                $i = 1;
            } else {
                $tags_row .= " ,'" . $t . "'";
            }
        }
        $tags_row .= "]::text[]";

        $i = 0;
        $attrs_row = "ARRAY[";
        foreach ($this->attributes as $attr) {
            if ($i == 0) {
                $attrs_row .= "cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
                $i = 1;
            } else {
                $attrs_row .= ", cast((" . $attr['type'] . ", '" . $attr['name'] . "', '" . $attr['value'] . "') AS nir.atrtype)";
            }
        }
        $attrs_row .= "]::nir.atrtype[]";
        //echo "SELECT * FROM nir.editdoc($this->id, '$this->name', $this->parent_id, ".$tags_row.", ".$attrs_row.")";
        $query = pg_query($this->link, "SELECT * FROM nir.editdoc($this->id, '$this->name', $this->parent_id, " . $tags_row . ", " . $attrs_row . ")");
        $result = pg_fetch_array($query);
        return $result; //возвращает id дока при успехе
    }

    //Получить документы каталога
    public function get_Documents_of_cat()
    {
        $this->parent_id = (int)$this->parent_id;
        $query = pg_query($this->link, "SELECT * FROM nir.get_docs_of_cat($this->parent_id)");
        $documents = array();
        while ($data = pg_fetch_array($query)) {
            $documents[] = array("id" => $data['id'], "name" => $data['name']);
        }
        return $documents; //возвращает массив документов (id, name)
    }

    //получение имени дока по id
    public function get_Document_Name_by_id()
    {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.get_doc_name_by_id($this->id)");
        $result = pg_fetch_array($query);
        //_fw_print_html_tag('pre', pg_last_error($this->link));
        //var_dump($result);
        return $result;
    }

    //Удаление документа (с удалением связей с тегами и атрибутами)
    public function deleteDocument()
    {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.dropdoc($this->id)");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Удаление документа';
        $inputs = 'ID документа: ' . $this->id;
        if ($result[0] == 1)
            $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result;    //возвращает 1 в случае успеха
    }

    //Удаление файла у дока по id дока
    public function dropFile()
    {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.drop_file_by_id_doc($this->id)"); //удаление из БД
        $result = pg_fetch_array($query);
        /*if($result[0] > 0) //если у дока был файл
        {
            //получает объект
            $query = pg_query($this->link, "SELECT * FROM nir.get_file_name($result[0])");
            $file = pg_fetch_array($query);
            $delete_file = $_SERVER['DOCUMENT_ROOT'].$file[0]['namefile'];
            unlink($delete_file); //физическое удаление с сервера
        }*/
        //запись лога
        $action = 'Удаление файла';
        $inputs = 'ID документа: ' . $this->id;
        if ($result[0] > 0)
            $res = 'Успех';
        else
            $res = 'Ошибка. У документа отсутствует файл';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает id удаленного файла или 0 если нет файла у дока
    }

    //Изменение имени документа
    public function changeName()
    {
        $this->id = (int)$this->id;
        $this->parent_id = (int)$this->parent_id;
        $this->name = trim(stripslashes(strip_tags($this->name)));
        $query = pg_query($this->link, "SELECT * FROM nir.changenamedoc($this->id, $this->parent_id, '$this->name')");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Изменение имени документа';
        $inputs = 'ID документа: ' . $this->id . ', ID рубрики: ' . $this->parent_id . ', новое имя документа: ' . $this->name;
        if ($result[0] == 1)
            $res = 'Успех';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //Возвращает 1 в случае успеха
    }

    //Загрузить файл на сервер (добавляет название файла в таблицу объектов и делает связь с документом)
    public function loadFile()
    {
        $this->id = (int)$this->id;
        $this->file_name = trim(stripslashes(strip_tags($this->file_name)));
        $query = pg_query($this->link, "SELECT * FROM nir.loadfile('$this->file_name', $this->id)");
        $result = pg_fetch_array($query);
        //запись лога
        $action = 'Загрузка файла на сервер';
        $inputs = 'Путь к файлу: ' . $this->file_name . ', ID документа: ' . $this->id;
        if ($result[0] > 0)
            $res = 'Успех';
        elseif ($result[0] == -1)
            $res = 'Ошибка. Такой файл уже привязан к документу';
        else
            $res = 'Ошибка';
        //set_user_log($action, $inputs, $res);
        return $result; //возвращает id имени файла (создается объект в таблице объектов с именем файла), -1, если такой файл уже есть у дока, 0 если просто не удалось создать файл
    }

    //Скачать файл (получить id и имя файла данного документа)
    public function downloadFile()
    {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.downloadfile($this->id)");
        $result = pg_fetch_array($query);
        return $result;
    }

    public function get_parent_path() {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "SELECT o_id, o_name, nir.get_parent_path(o_id) as path FROM nir.all_docs_view WHERE o_id=$this->id");
        $result = pg_fetch_array($query);
        return $result;
    }

    //поиск документов по тегам
    public function search_doc_by_tag()
    {
        $tags_row = "ARRAY[";
        $i = 0;
	$id = array();
        foreach ($this->tegs as $t) {
            if ($i == 0) {
                $tags_row .= "'" . $t . "'";
                $i = 1;
            } else {
                $tags_row .= " ,'" . $t . "'";
            }
        }
        $tags_row .= "]::text[]";
        $this->id = (int)$this->id;
        if ($this->id) {
            //$query = pg_query($link, "(select id from nir.find_doc_by_tag($tags_row))");
            $query = pg_query($this->link, "(SELECT * FROM nir.find_doc_by_tag($this->id, $tags_row)where (select isReader from nir.get_access(o_id)))");
            while ($data = pg_fetch_array($query)) {
                $id[] = array("id" => $data['o_id'], "name" => $data['o_name']);
            }
        }
        else {
            //$query = pg_query($link, "(select id from nir.find_doc_by_tag($tags_row))")
            $query = pg_query($this->link, "(SELECT * FROM nir.find_doc_by_tag($tags_row) where (select isReader from nir.get_access(o_id)))");
            while ($data = pg_fetch_array($query)) {
                $id[] = array("id" => $data['o_id'], "name" => $data['o_name']);
            }
        }
        return $id;
    }

    public function search_doc_by_attr() {
        $list = array();
        $id_attr = $this->id;
        $id_place = $this->parent_id;
        if (empty($id_place))
        {
            $query = pg_query($this->link, "SELECT obj_id, obj_name, obj_type  FROM nir.atrs_view where  atr_id=$id_attr and obj_type=5");
            while($res = pg_fetch_array($query))
            {
                $list[] = array("id" => $res['obj_id'], "name" => $res['obj_name']);
            }
            return $list;
        }
        else
        {
            $query = pg_query($this->link, "SELECT obj_id, obj_name, obj_type FROM nir.atrs_view where atr_id=$id_attr and obj_type=5 
                                        and obj_id in (SELECT o_id from nir.get_objs_in_catalog($id_place) )
                                        order by obj_name");
            while($res = pg_fetch_array($query))
            {
                $list[] = array("id" => $res['obj_id'], "name" => $res['obj_name']);
            }
            return $list;
        }
    }

    public function getIdTop ()
    {
        $query = pg_query($this->link, "(SELECT * FROM nir.get_id_top($this->id))");
        $result = pg_fetch_array($query);
        return $result[0];
    }

    //получение типа самого верхнего родителя
    public function getTypeFirstParent() {
        $this->id = (int)$this->id;
        $query = pg_query($this->link, "select get_top_type from nir.get_top_type($this->id)");
        $result = pg_fetch_array($query);
        return $result[0];
    }

    public function copyDoc() {
        $this->id = (int)$this->id;
        $this->parent_id = (int)$this->parent_id;
        $query = pg_query($this->link, "select * from nir.copy_doc($this->id, '".$this->name."', $this->parent_id)");
        $result = pg_fetch_array($query);
        return $result; // возвращает id нового дока
    }

    public function getParentCatalog() {
        $this->id = (int) $this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.get_parent_catalog($this->id)");
        $catalog = array();
        $data = pg_fetch_array($query);
        $catalog = array("id" => $data['id'], "name" => $data['name'], "type" => $data['type']);
        return $catalog; //Возвращает массив (id, name) с каталогом
    }
    


}
