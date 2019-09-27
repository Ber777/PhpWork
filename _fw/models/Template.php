<?php
class Template
{
	var $id;
	var $id_type;
	var $name;
	var $link;

	public function get_user_templates() {
		$query = "SELECT o_id, o_name, o_id_type, user_id, user_id_system FROM nir.all_templates_view where o_id_type=$this->id_type and user_id_system=current_user order by o_name";
		$result = pg_query($this->link, $query);
		$temps = array();
		while($data = pg_fetch_array($result))
		{
			$temps[] = array("id" => $data['o_id'], "name" => $data['o_name']);
		}
		return $temps;
	}

	public function copy_template()
	{
//		$name1 = trim(stripslashes($name)); //название создаваемого шаблона
		$query = pg_query($this->link, "SELECT nir.copy_template( $this->id)");
		$result = pg_fetch_array($query);
		return $result; //возвращает -1, если с таким именем шаблон уже есть и id шаблона в случае успеха
	}

	public function copy_alert()
	{
		$query = pg_query($this->link, "SELECT nir.copy_alert( $this->id)");
		$result = pg_fetch_array($query);
		return $result; //возвращает -1, если с таким именем шаблон уже есть и id шаблона в случае успеха
	}

	public function search_template() {
		$array_templates = array();
		$query = "SELECT o_id, o_name, o_id_type, user_id, user_id_system FROM nir.all_templates_view where o_id_type=$this->id_type and upper(o_name) like upper('%$this->name%') order by o_name";
		$result = pg_query($this->link, $query);
		while($data = pg_fetch_array($result))
		{
			$array_templates[] = array("id" => $data['o_id'], "name" => $data['o_name']);
		}
		return $array_templates;
	}

	public function get_name_template() {
		$query = "SELECT o_name FROM nir.all_templates_view where o_id=$this->id";
		$result = pg_query($this->link, $query);
		$result_name = pg_fetch_array($result);
		return $result_name[0];
	}

}
