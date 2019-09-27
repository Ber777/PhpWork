<?php
class Catalog
{
	//??????????
	var $name='';
	var $id = 0;
	var $parent_id = 0;
	var $link;
	var $tegs = array(); //массив тегов (массив названий)
	var $attributes = array(); //массив атрибутов ()тип, название, значение


	public function __construct(){} //Конструктор

	public function addCatalog()
	{
		$this->parent_id = (int) $this->parent_id;
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tegs as $t)
		{
			if($i == 0)
			{
				$tags_row .= "'".$t."'";
				$i = 1;
			}
			else
			{
				$tags_row .= " ,'".$t."'";
			}
		}
		$tags_row .= "]::text[]";
		
		$i = 0;
		$attrs_row = "ARRAY[";
		foreach($this->attributes as $attr)
		{
			if($i == 0)
			{
				$attrs_row .= "cast((".$attr['type'].", '".$attr['name']."', '".$attr['value']."') AS nir.atrtype)";
				$i = 1;
			}
			else
			{	
				$attrs_row .= ", cast((".$attr['type'].", '".$attr['name']."', '".$attr['value']."') AS nir.atrtype)";
			}
		}
		$attrs_row .= "]::nir.atrtype[]";
		$query = pg_query($this->link, "SELECT * FROM nir.addcatalog_ext('$this->name', $this->parent_id, ".$tags_row.", ".$attrs_row.")");        
		$result = pg_fetch_array($query);
		
		//запись лога
		$action = 'Добавление рубрики';
		$inputs = 'ID родительской рубрики: '.$this->parent_id.', Название: '.$this->name;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка. Такая рубрика уже есть в данной рубрике';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id дока при успешном добавлении и -1, если такой каталог(с таким именем) уже есть
	}
	
	public function editCatalog()
	{
		$this->id = (int) $this->id;
		$this->parent_id = (int) $this->parent_id;
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tegs as $t)
		{
			if($i == 0)
			{
				$tags_row .= "'".$t."'";
				$i = 1;
			}
			else
			{
				$tags_row .= " ,'".$t."'";
			}
		}
		$tags_row .= "]::text[]";
		
		$i = 0;
		$attrs_row = "ARRAY[";
		foreach($this->attributes as $attr)
		{
			if($i == 0)
			{
				$attrs_row .= "cast((".$attr['type'].", '".$attr['name']."', '".$attr['value']."') AS nir.atrtype)";
				$i = 1;
			}
			else
			{	
				$attrs_row .= ", cast((".$attr['type'].", '".$attr['name']."', '".$attr['value']."') AS nir.atrtype)";
			}
		}
		$attrs_row .= "]::nir.atrtype[]";
		$query = pg_query($this->link, "SELECT * FROM nir.editcatalog($this->id, '$this->name', $this->parent_id, ".$tags_row.", ".$attrs_row.")");
		$result = pg_fetch_array($query);
		return $result; //возвращает id дока при успехе
	}

	public function delCatalog()
	{
		$this->id = (int) $this->id;
		$query = pg_query($this->link, "SELECT * FROM nir.drop_catalog($this->id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление рубрики';
		$inputs = 'ID рубрики: '.$this->id;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result;	//Возвращает 1
	}

	//Получить Родительский каталог
	public function getParentCatalog()
	{
		$this->id = (int) $this->id;
		$query = pg_query($this->link, "SELECT * FROM nir.get_parent_catalog($this->id)");
		$catalog = array();
		$data = pg_fetch_array($query);
		$catalog = array("id" => $data['id'], "name" => $data['name'], "type" => $data['type']);
		return $catalog; //Возвращает массив (id, name) с каталогом
	}

	//Получить вложенные каталоги
	public function get_child_Catalogs()
	{
		$this->parent_id = (int) $this->parent_id;
		$query = pg_query($this->link, "SELECT * FROM nir.get_cat_of_catalog($this->parent_id)");
		$catalogs = array();
		while($data = pg_fetch_array($query))
		{
			$catalogs[] = array("id" => $data['id'], "name" => $data['name']);
		}
		return $catalogs; //Возвращает масив каталогов
	}
	
	//Смена имени каталога
	public function changeName()
	{
		$this->id = (int) $this->id;
		$this->parent_id = (int) $this->parent_id;
		$this->name = stripslashes($this->name);
		$query = pg_query($this->link, "SELECT * FROM nir.changenamecatalog($this->id, $this->parent_id, '$this->name')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Изменение имени рубрики';
		$inputs = 'ID рубрики: '.$this->id.', ID родительской рубрики: '.$this->parent_id.', новое имя рубрики: '.$this->name;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка. Рубрика с таким именем уже существует';
		//set_user_log($action, $inputs, $res);
		return $result; // 1 в случае успеха, -1 если такой каталог есть уже
	}
	
	public function get_this_catalog() //вернет id и name
	{
		$this->id = (int) $this->id;
		$query = pg_query($this->link, "SELECT * FROM nir.get_catalog($this->id)");

		while($data = pg_fetch_array($query))
		{
			$catalog = array("id" => $this->id, "name" => $data['get_catalog']);
		}
		return $catalog;
	}

	public function get_parent_path() {
		$this->id = (int)$this->id;
		$query = pg_query($this->link, "SELECT o_id, o_name, nir.get_parent_path(o_id) as path FROM nir.all_catalogs_view WHERE o_id=$this->id");
		$result = pg_fetch_array($query);
		return $result;
	}

	//получение первого родителя
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

	//копирование каталогов
	public function copyCatalog() {
		$this->id = (int)$this->id;
		$this->parent_id = (int)$this->parent_id;
		$query = pg_query($this->link, "select * from nir.copy_catalog($this->id, $this->parent_id)");
		while($data = pg_fetch_array($query))
		{
			$files[] = array("id" => $data['id_old'], "path" => $data['file_old'], "id_new" => $data['id_new']);
		}
		return $files;
	}

	public function getOwner() {
	    $this->id = (int)$this->id;
	    $query = pg_query("select nir.get_owner($this->id)");
	    if ($data = pg_fetch_array($query)) {
	        $user_id = $data['get_owner'];
        }

        return $user_id;
    }
	
}
