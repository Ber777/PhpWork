<?php
class Database
{
	var $name='';
	var $id = 0;
	var $link;
	var $user_id = 0; //id пользователя
	var $tags = array(); //массив тегов (названия)
	var $attributes = array(); //массив атрибутов(id типа, название, значение)
	var $DB = 1; //флаг Базы данных (если $this->DB = 1, то значит не KZ, а БД)

	public function __construct(){} //Конструктор

	public function addTemplate()
	{
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tags as $t)
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
       	//echo "SELECT * FROM nir.add_template_kz('$this->name', ".$tags_row.", ".$attrs_row.")";
		$query = pg_query($this->link, "SELECT * FROM nir.add_template_kz('$this->name', ".$tags_row.", ".$attrs_row.")"); //в атрибуты подавать без значений (значения - пустые строки)
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Добавление шаблона КЗ';
		$inputs = 'Название: '.$this->name;
		if($result[0] > 0)
			$res = 'Успех';
		elseif($result[0] == -1)
			$res = 'Ошибка. Шаблон с таким именем уже существует';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id шаблона и -1 если такой есть уже
	}
	
	public function editTemplate() //не знаю, заработает или нет (нужно потестить)
	{
		$this->id = (int) $this->id; //id шаблона
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tags as $t)
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
      	//echo "SELECT * FROM nir.edit_template($this->id, '$this->name', ".$tags_row.", ".$attrs_row.")";
		$query = pg_query($this->link, "SELECT * FROM nir.edit_template($this->id, '$this->name', ".$tags_row.", ".$attrs_row.")"); //в атрибуты подавать без значений (значения - пустые строки)
		$result = pg_fetch_array($query);
		return $result; 
	}
	
	public function delTemplate()
	{
		$this->id = (int) $this->id; //id шаблона
		$query = pg_query($this->link, "SELECT * FROM nir.drop_template($this->id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление шаблона КЗ';
		$inputs = 'ID шаблона: '.$this->id;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает 1 в случае успеха
	}
	
	//получение списка шаблонов
	public function get_all_kz_temp()
	{
		$query = pg_query($this->link, "SELECT * FROM nir.get_all_templates_kz()");
		$temps = array();
		while($data = pg_fetch_array($query))
		{
			$temps[] = array("id" => $data['id'], "name" => $data['name']);
		}
		return $temps;
	}
	
	public function addKZ()
	{
		$this->DB = (int) $this->DB;
		$this->user_id = (int) $this->user_id;
		$this->name = stripslashes($this->name);
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tags as $t)
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
		$query = pg_query($this->link, "SELECT * FROM nir.addkz($this->DB, '$this->name', $this->user_id, ".$tags_row.", ".$attrs_row.")");
		$result = pg_fetch_array($query);
		//запись лога
		if($this->DB == 1)
			$action = 'Добавление БД';
		else
			$action = 'Добавление КЗ';
		$inputs = 'Название: '.$this->name;
		if($result[0] > 0)
			$res = 'Успех';
		elseif($result[0] == -1)
			$res = 'Ошибка. КЗ с таким именем уже есть';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id если все ок, -1, если такая кз есть уже
	}
	
	public function editKZ() 
	{
		$this->DB = (int) $this->DB;
		$this->id = (int) $this->id;
		$this->name = trim(stripslashes(strip_tags($this->name)));
		$tags_row = "ARRAY[";
		$i = 0;
		foreach($this->tags as $t)
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
		$query = pg_query($this->link, "SELECT * FROM nir.editkz($this->DB, $this->id, '$this->name', ".$tags_row.", ".$attrs_row.")");
		$result = pg_fetch_array($query);
		return $result; //возвращает id дока при успехе
	}
	

	
	//Смена имени БД
	public function changeNameDB()
	{
		$this->id = (int) $this->id;
		$this->name = stripslashes($this->name);
		$query = pg_query($this->link, "SELECT * FROM nir.changenamedb($this->id, '$this->name')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Изменение имени БД';
		$inputs = 'ID БД: '.$this->id.', новое имя БД: '.$this->name;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает 1 в случае успеха
	}
    
    
    
	
	//получение списка БД
    public function getReadListDB()
    {
        $query = pg_query($this->link, "SELECT * FROM nir.get_db() where (select isReader from nir.get_access(iddb))");
		$list = array();
		while($data = pg_fetch_array($query))
		{
			$list[] = array("id" => $data['iddb'], "name" => $data['namedb']);
		}
		return $list;
    }
    
	public function getListDB()
    {
        $query = pg_query($this->link, "SELECT * FROM nir.get_db()");
		$list = array();
		while($data = pg_fetch_array($query))
		{
			$list[] = array("id" => $data['iddb'], "name" => $data['namedb']);
		}
		return $list;
    }
	
	public function get_user_kz()
	{
		$this->user_id = (int) $this->user_id;
		$query = pg_query($this->link, "SELECT * FROM nir.get_kz($this->user_id)");
		$ks = array();
		while($data = pg_fetch_array($query))
		{
			$kz[] = array("id" => $data['idkz'], "name" => $data['namekz']);
		}
		return $kz;
	}
   
	public function get_all_kz()
	{
		$this->user_id = (int) $this->user_id;
		$query = pg_query($this->link, "SELECT * FROM nir.all_kzs_view");
		$kz = array();
		while($data = pg_fetch_array($query))
		{
			$kz[] = array("id" => $data['o_id'], "name" => $data['o_name'], "isOwner" => $data['isowner'], "user_id" => $data['user_id'], "iuser_name" => $data['user_name']);
		}
		//echo '<pre>'; print_r($kz); echo '</pre>';
		return $kz;
	}
    
    public function get_all_kzRead()
	{
		$this->user_id = (int) $this->user_id;
		$query = pg_query($this->link, "SELECT * FROM nir.all_kzs_view where ((select isReader from nir.get_access(o_id)) or (select isOwner from nir.get_access(o_id)))");
		$kz = array();
		while($data = pg_fetch_array($query))
		{
			$kz[] = array("id" => $data['o_id'], "name" => $data['o_name'], "isOwner" => $data['isowner'], "user_id" => $data['user_id'], "iuser_name" => $data['user_name']);
		}
		//echo '<pre>'; print_r($kz); echo '</pre>';
		return $kz;
	}
   
	
	//удаление КЗ и ДБ
	public function delete()
	{
		$this->id = (int) $this->id;
		$query = pg_query($this->link, "SELECT * FROM nir.drop_catalog($this->id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление КЗ/БД';
		$inputs = 'ID объекта: '.$this->id;
		if($result[0] == 1)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result;	//Возвращает 1
	}

}
