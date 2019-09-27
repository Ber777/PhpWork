<?php
class SearchTemplate
{
	var $id = 0; //id шаблона
	var $name = ''; //Название шаблона
	var $sql_txt = ''; //текст sql
	var $link;
	
	public function __construct(){} //Конструктор
	
	//выборка всех шаблонов
	public function get_all_templates()
	{
		$query = pg_query($this->link, "SELECT o_id, o_name, sql_txt FROM nir.all_search_templates_view");
		$temp = array();
		while($data = pg_fetch_array($query))
		{
			$temp[] = array("id" => $data['o_id'], "name" => $data['o_name'], "sql" => $data['sql_txt']);
		}
		return $temp; //возвращает массив шаблонов
	}

	public function get_my_templates()
	{
		$query = pg_query($this->link, "SELECT o_id, o_name, sql_txt FROM nir.all_search_templates_view where user_id_system=current_user order by o_name" );
		$temp = array();
		while($data = pg_fetch_array($query))
		{
			$temp[] = array("id" => $data['o_id'], "name" => $data['o_name'], "sql" => $data['sql_txt']);
		}
		return $temp; //возвращает массив шаблонов
	}
   
   	public function get_by_id()
	{
		$query = pg_query($this->link, "SELECT o_id, o_name, sql_txt FROM nir.all_search_templates_view where o_id=$this->id" );
		$temp = array();
		if($data = pg_fetch_array($query))
		{
			$this->id = $data['o_id'];
			$this->name = $data['o_name'];
			$this->sql_txt = $data['sql_txt'];
			$array_result = array(
				'id' => $data['o_id'],
				'name' => $data['o_name'],
				'sql_text' =>$data['sql_txt']
			);
			return $array_result;
		}
          else
      		return false; 
	}
	
	//создание шаблона
	public function add_template()
	{
		$this->name = trim(stripslashes($this->name));
	//	$this->sql_txt = trim(stripslashes($this->sql_txt));
		$query = pg_query($this->link, "SELECT * FROM nir.add_search_template('$this->name', '$this->sql_txt')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Создание шаблона поиска';
		$inputs = 'Название: '.$this->name;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result;	//Возвращает id созданного шаблона, или -1, если шаблон с таким именем уже есть
	}
	
	//удаление шаблона
	public function drop_template()
	{
		$this->id = (int) $this->id;
		$query = pg_query($this->link, "SELECT * FROM nir.drop_search_template($this->id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление шаблона поиска';
		$inputs = 'ID шаблона: '.$this->id;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id удаленного шаблона
	}
	
	//изменение названия
	public function change_name()
	{
		$this->id = (int) $this->id; //id шаблона, у которого меняем имя
		$this->name = trim(stripslashes($this->name)); //новое имя
		$query = pg_query($this->link, "SELECT * FROM nir.changename_search_template($this->id, '$this->name')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Изменение имени шаблона поиска';
		$inputs = 'ID шаблона: '.$this->id.', новое имя шаблона: '.$this->name;
		if($result[0] > 0)
			$res = 'Успех';
		elseif($result[0] == -1)
			$res = 'Ошибка. Шаблон с таким именем уже существует';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает -1, если такое имя уже есть, id шаблона в случае успеха, 0 в случае неудачи
	}

    public function edit_template() {
        $this->id = (int) $this->id; //id шаблона
        $this->name = trim(stripslashes($this->name)); //новое имя
        $this->sql_txt = (string) $this->sql_txt;
        $query = pg_query($this->link, "SELECT * FROM nir.edit_search_template($this->id, '$this->name', '$this->sql_txt')");
        $result = pg_fetch_array($query);
        return $result;
    }
}
