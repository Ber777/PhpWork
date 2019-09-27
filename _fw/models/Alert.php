<?php
class Alert
{
    var $id = 0; //id шаблона
    var $name = ''; //Название шаблона
    var $sql_txt = ''; //текст sql
    var $link;

    public function __construct(){

    } //Конструктор

    //выборка всех шаблонов
    public function get_all_templates()
    {
        $query = pg_query($this->link, "SELECT o_id, o_name, o_id_type, user_id, user_id_system  FROM nir.all_templates_view  where o_id_type=9 order by o_name;");
        $temp = array();
        while($data = pg_fetch_array($query))
        {
            $temp[] = array("id" => $data['o_id'], "name" => $data['o_name'], "sql" => isset($data['sql_txt']));
        }
        return $temp; //возвращает массив шаблонов
    }

    public function get_my_templates()
    {
        $query = pg_query($this->link, "SELECT o_id, o_name, o_id_type, user_id, user_id_system  FROM nir.all_templates_view where o_id_type=16 and user_id_system=current_user order by o_name;" );
        $temp = array();
        while($data = pg_fetch_array($query))
        {
            $temp[] = array("id" => $data['o_id'], "name" => $data['o_name'], "sql" => isset($data['sql_txt']));
        }
        return $temp; //возвращает массив шаблонов
    }

    //создание шаблона
    public function add_template()
    {
        $this->name = trim(stripslashes($this->name));
        //	$this->sql_txt = trim(stripslashes($this->sql_txt));
        //echo "SELECT * FROM nir.add_alert('$this->name', '$this->sql_txt')";
        $query = pg_query($this->link, "SELECT * FROM nir.add_alert('$this->name', '$this->sql_txt')");
        $result = pg_fetch_array($query);
        return $result;	//Возвращает id созданного шаблона, или -1, если шаблон с таким именем уже есть
    }

    //удаление шаблона
    public function drop_template()
    {
        $this->id = (int) $this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.drop_alert($this->id)");
        $result = pg_fetch_array($query);
        return $result; //возвращает id удаленного шаблона
    }

    //изменение названия
    public function change_name()
    {
        $this->id = (int) $this->id; //id шаблона, у которого меняем имя
        $this->name = trim(stripslashes($this->name)); //новое имя
        $query = pg_query($this->link, "SELECT * FROM nir.changename_alert($this->id, '$this->name'::text)");
        $result = pg_fetch_array($query);
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

    public function search_by_template($idTemp)
    {
        $query = pg_query($this->link, "(SELECT * FROM nir.all_alerts_view WHERE o_id = $idTemp) UNION (SELECT * FROM nir.all_search_templates_view WHERE o_id = $idTemp)");
        $data = pg_fetch_array($query);
        $txt = $data['sql_txt'];
        // echo $txt;
        $str = json_decode($txt) ;
        //    echo $str;
        $sql = $this->getSql($str);
        //echo $sql;
        $query = pg_query($this->link,  $sql); //"SELECT o_id, o_name, o_id_type, user_id, user_id_system  FROM nir.all_templates_view where o_id_type=16 and user_id_system=current_user order by o_name;" );
        $temp = array();
        while($data = pg_fetch_array($query))
        {
            $temp[] = array("id" => $data['o_id'], "name" => $data['o_name'], "sql" => isset($data['sql_txt']));
        }
        return $temp; //возвращает массив шаблонов
    }

    public function isActive()
    {
        $query = pg_query($this->link, "(select * from nir.all_alerts_view where o_id=$this->id) union  (select * from nir.all_search_templates_view where o_id=$this->id) ");
        $data = pg_fetch_array($query);
        $txt = $data['sql_txt'];
        $str = json_decode($txt) ;
        if ($str) {
            $sql = $this->getSql($str);
            $query = pg_query($this->link, $sql);

	#echo $sql;

            if( ($query > 0) && (pg_num_rows($query)>0)) {
                $temp=true;
            }
            else {
                $temp=false;
            }
        }

        return $temp;
    }

    public function get_sqljson_by_id($id)
    {
        $query = pg_query($this->link, "(SELECT o_id, o_name, sql_txt FROM nir.all_search_templates_view where o_id=$id) union (SELECT o_id, o_name, sql_txt FROM nir.all_alerts_view where o_id=$id)");
        if($data = pg_fetch_array($query))
        {
            $sql_txt = $data['sql_txt'];
            return $sql_txt;
        }
        else
            return "";
    }

    public function getSql($data)
    {

        if (count($data)) {
            $str = "SELECT o_id, o_name FROM nir.nir_object where o_id_type=5 and ( ";
            $top=true;
            foreach( $data as $data2 )
            {
                if( $top )
                {
                    $top=false;
                }else {
                    $str=$str." or ";
                }
                $str=$str." ( ";
                $first=true;
                if (!empty($data2)) {

                }
                foreach ($data2 as $item)
                {
                    if( $first )
                    {
                        $first=false;
                    }else {
                        $str=$str." and ";
                    }
                    if ($item->id == 1) {
                        # code...
                        $str = $str . " exists(SELECT tag_id FROM nir.tags_view where upper(tag_name)=upper('". $item->name ."') and obj_id=o_id) ";
                    }
                    else if( $item->id == 3) {
                        $str = $str . " not  exists(SELECT tag_id FROM nir.tags_view where  upper(tag_name)=upper('". $item->name ."') and obj_id=o_id) ";
                    }
                    else if( $item->id == 2) {
                        if( $item->relation == "equal") {
                            $str=$str."exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and nir.is_equal_value( atr_type, atr_value,   '". $item->number."' ) )";
                        }
                        else if( $item->relation == "notequal") {
                            $str=$str."not exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and nir.is_equal_value( atr_type, atr_value,   '". $item->number."' ) )";
                        }
                        else if( $item->relation == "<") {
                            $str=$str."exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and nir.less_then( atr_type, atr_value,   '".$item->number."' ) ) ";
                        }
                        else if( $item->relation == ">") {
                            $str=$str."exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and nir.great_then( atr_type, atr_value,   '".$item->number."' ) ) ";
                        }
                        else if( $item->relation == "like") {
                            $str=$str."exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and upper(atr_value) like ('%".$item->number."%') ) ";
                        }
                    }
                }
                $str = $str. " ) ";
            }
            $str = $str. " ) ";
//            echo $str;
        }

        return $str;
    }

    public function get_by_id()
    {
        $query = pg_query($this->link, "SELECT o_id, o_name, sql_txt FROM nir.all_alerts_view where o_id=$this->id" );
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


}
