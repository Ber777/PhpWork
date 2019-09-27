<?php
class CatalogTemplate
{
	var $temp_id = 0; //id шаблона
	var $catalog_id = 0; //id каталога
	var $name_template = ''; //название шаблона
	var $name_catalog = ''; //название каталога
	var $parent_id = 0; //id родительского каталога
	var $link;

	public function __construct()
	{
	} //Конструктор

	//получение списка
	public function get_all_templates()
	{
		$query = pg_query($this->link, "SELECT o_id, o_name FROM nir.all_catalog_templates_view");
		$temp = array();
		while ($data = pg_fetch_array($query)) {
			$temp[] = array("id" => $data['o_id'], "name" => $data['o_name']);
		}
		return $temp; //возвращает массив шаблонов
	}

	//клонирование каталога в шаблон (со всеми подкаталогами с сохранением иерархии)
	public function clone_cat_to_temp()
	{
		$this->name_template = trim(stripslashes($this->name_template)); //название создаваемого шаблона
		$this->catalog_id = (int)$this->catalog_id; //id каталога, из которого создать шаблон
		$query = pg_query($this->link, "SELECT * FROM nir.clone_catalog_to_template('$this->name_template', $this->catalog_id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Клонирование рубрики в шаблон';
		$inputs = 'ID рубрики: '.$this->catalog_id.', название шаблона: '.$this->name_template;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает -1, если с таким именем шаблон уже есть и id шаблона в случае успеха
	}

	//создание каталога на основе шаблона
	public function add_catalog_by_temp()
	{
		$this->name_catalog = trim(stripslashes($this->name_catalog)); //название создаваемого каталога
		$this->parent_id = (int)$this->parent_id; //id родительского каталога, где создаем каталог
		$this->temp_id = (int)$this->temp_id; //id шаблона, на основе которого создаем каталог
		$query = pg_query($this->link, "SELECT * FROM nir.addcatalog_by_template('$this->name_catalog', $this->parent_id, $this->temp_id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Создание рубрики из шаблона';
		$inputs = 'Название создаваемой рубрики: '.$this->name_catalog.', ID родительской рубрики: '.$this->parent_id.', ID шаблона: '.$this->temp_id;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id созданного каталога
	}

	//изенеие имени шаблона
	public function change_name()
	{
		$this->temp_id = (int) $this->temp_id; //id шаблона, у которого меняем имя
		$this->name_template = trim(stripslashes($this->name_template)); //новое имя шаблона
		$query = pg_query($this->link, "SELECT * FROM nir.changename_cat_template($this->temp_id, '$this->name_template')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Изменение имени шаблона рубрики';
		$inputs = 'ID шаблона: '.$this->temp_id.', новое имя шаблона: '.$this->name_template;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает -1 если такое имя уже есть, id если все ок
	}

	//удалить шаблон
	public function drop_template()
	{
		$this->temp_id = (int) $this->temp_id;
		$query = pg_query($this->link, "SELECT * FROM nir.dropcat_template($this->temp_id)");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Удаление шаблона рубрики';
		$inputs = 'ID шаблона: '.$this->temp_id;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id удаленного шаблона
	}

	//создание шаблона каталога 
	public function add_catalog_temp()
	{
		$this->name_catalog = trim(stripslashes($this->name_catalog)); //название создаваемого каталога
		$query = pg_query($this->link, "SELECT * FROM nir.add_catalog_template('$this->name_catalog')");
		$result = pg_fetch_array($query);
		//запись лога
		$action = 'Создание шаблона рубрики';
		$inputs = 'Название: '.$this->name_catalog;
		if($result[0] > 0)
			$res = 'Успех';
		else
			$res = 'Ошибка';
		//set_user_log($action, $inputs, $res);
		return $result; //возвращает id созданного каталога
	}
   
}
