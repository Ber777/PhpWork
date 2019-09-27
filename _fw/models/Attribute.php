<?php
class Attribute
{
	var $id = 0;
	var $name = ''; //имя атрибута
	var $type_id = 0; //тип атрибута (строка, дата, число, или маска - добавляется как атрибут)
	var $value; //значение
	var $obj_id = 0; //id объекта
	var $link;
	var $attr_list = array(); //массив атрибутов (не знаю пока в каком виде подавать)
	var $place_id;
    //1 int
    //2 varchar
    //3 date
	public function __construct() {}

	public function add_new_attr() //добавляет новый, не существующий атрибут, и линк сам на себя для типа
	{
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$this->type_id = (int) $this->type_id;
		$query = pg_query($this->link, "SELECT * FROM nir.addattr('$this->name', $this->type_id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Добавление нового атрибута в БД';
		$inputs = 'Название: '.$this->name.', ID типа: '.$this->type_id;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id атрибута
	}
	
	public function add_to_object() //тут поменяется - будет одна процедура для массива атрибутов сразу, но там что то пока не понятно и поэтому нужно в цикле вызывать эту функцию для всех атрибутов
	{
		$this->name = stripslashes($this->name);
		$this->obj_id = (int) $this->obj_id;
		$this->type_id = (int) $this->type_id;
		if($this->type_id == 1) //интегер
		{
			$this->value = (int) $this->value;
			$query = pg_query($this->link, "SELECT * FROM add_attr_to_doc_int('$this->name', $this->value, $this->obj_id)");
			$result = pg_fetch_array($query);
		}
		if($this->type_id == 2) //варчар
		{
			$this->value = stripslashes($this->value);
			$query = pg_query($this->link, "SELECT * FROM add_attr_to_doc_varchar('$this->name', '$this->value', $this->obj_id)");
			$result = pg_fetch_array($query);
		}
		if($this->type_id == 3) //дата
		{
			$query = pg_query($this->link, "SELECT * FROM add_attr_to_doc_date('$this->name', $this->value, $this->obj_id)");
			$result = pg_fetch_array($query);
		}
		return $result; //возвращает id значения
		
	}
	
	//Удаление атрибута у документа (мб сделать удаление списка атрибутов)
	public function del_attr_from_doc()
	{
		$this->id = (int) $this->id;
		$this->obj_id = (int) $this->obj_id;
		$query = pg_query($this->link, "SELECT * FROM nir.dropatr($this->id, $this->obj_id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление атрибута у объекта';
		$inputs = 'ID атрибута: '.$this->id.', ID объекта: '.$this->obj_id;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		set_user_log($action, $inputs, $res);
		return $result; //возвращает 1 в случае успеха и 0 в случае неудачи	
	}

	public function get_attributes_of_obj() 
	{
		$this->obj_id = (int) $this->obj_id;
		$query = pg_query($this->link, "SELECT * FROM nir.get_atrs_obj($this->obj_id)");
		$attributes = array();
		while($data = pg_fetch_array($query))
		{
			$attributes[] = array( "id" => $data['atr_id'], "name" => $data['atr_name'], "type" => $data['atr_type'], "value" => $data['atr_value'] );
		}
		return $attributes; //неизвестно что вернет (в процедуре возвращает таблицу) - скорее всего будет массив массивов, как и нужно
	}
	
	public function get_all_attr() 
	{
		$query = pg_query($this->link, "SELECT * FROM nir.getattrlist()");
		$attrs = array();
		while($data = pg_fetch_array($query))
		{
			$attrs[] = array("id" => $data['id'], "name" => $data['name'], "type" => $data['type']);
		}
		return $attrs; //возвращает массив атрибутов (id, name, type)
	}

	function get_list_attributes_in() {
		$query = pg_query($this->link, "SELECT distinct atr_id, atr_name, upper(atr_name), atr_typr as atr_type FROM nir.atrs_view
                                    where obj_id in (SELECT o_id from nir.get_objs_in_catalog($this->place_id) )
                                    order by upper(atr_name)");
		while($result = pg_fetch_array($query))
		{
			$list[] = array("id" => $result['atr_id'], "name" => $result['atr_name'], "type" => $result['atr_type']);
		}
		return $list;
	}

	function edit_name_attr() {
		$query = pg_query($this->link, "SELECT nir.changename_attr($this->id, '$this->name')");
		$result = pg_fetch_array($query);
		return $result;
	}

	function drop_attr() {
		$query = pg_query($this->link, "select nir.drop_attr($this->id)");
		$result = pg_fetch_array($query);
		return $result;
	}

	function add_attr() {
		$query = pg_query($this->link, "select nir.add_newattr('$this->name', $this->type_id::smallint)");
		$result = pg_fetch_array($query);
		return $result;
	}
	
}
