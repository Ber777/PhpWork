<?php
class BigSearch {
    var $data; //данные от клиента для большого поиска
    var $link; //ссылка на БД
    var $descriptor_id; //id, обозначающий тип дескриптора (тег, атрибут, отсутствие тега)
    var $key; //ключевое слово для автодополнения
    
    public function __construct(){} //Конструктор
    
    
    
    //Возвращает строку SQL-запроса для объекта JSON-данных от клиента
    public function getSql( $data)
    {
      $str = "select o_id, o_name from nir.nir_object where o_id_type=5 and ( ";
      $top=true;
      foreach( $data as $data2 ){
          
          if(!empty($data2)){
           if( $top )
           {
                        $top=false;
            }else {                                              
                       $str=$str." or ";
            }
           $str=$str." ( ";                     
           $first=true;
        foreach ($data2 as $item)
        {
            
        if( $first ){
            $first=false;
            
        }else {
            
            $str=$str." and ";
        }
        if ($item->id==1) {
					# code...
		     $str = $str . " exists(SELECT tag_id FROM nir.tags_view where  upper(tag_name)=upper('". $item->name ."') and obj_id=o_id) "; 
		}
		else if( $item->id==3) {
		  
            $str = $str . " not  exists(SELECT tag_id FROM nir.tags_view where  upper(tag_name)=upper('". $item->name ."') and obj_id=o_id) "; 
		}
		else if( $item->id==2) {
		  
            if( $item->relation == "=") {
                
                $str=$str."exists(SELECT atr_id  FROM nir.atrs_view_2 where  o_id = obj_id and upper(atr_name)=upper('".$item->name."') and nir.is_equal_value( atr_type, atr_value,   '". $item->number."' ) )";                          
            }
            else if( $item->relation == "!=") {
                
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
        }
        
        $str = $str. " ) ";
        //echo $str;
        return $str;
    
    }        
    
    
    //Возвращает массив документов
    public function getBigSearchData(){
        
        $str = $this->getSql($this->data);
         
        $query = pg_query($this->link,  $str); //"SELECT o_id, o_name, o_id_type, user_id, user_id_system  FROM nir.all_templates_view where o_id_type=16 and user_id_system=current_user order by o_name;" );
        $queryData = array();
        while( $dta = pg_fetch_array($query)){
            $query_doc = new Document();
            $query_doc->id = isset($dta['id']);
            $query_doc->link = $this->link;
            $catalog = $query_doc->getParentCatalog();   
 			$queryData[] = array("id" => $dta['o_id'],
                                 "name" => $dta['o_name'],
                                 "catalog"=>$catalog['name']);
  		}
        
        return $queryData;     

    }
    
    
    //Формирует текст запроса для автодополнения
    public function getAutocompleteSql() {
        
        $descriptors_names_for_sql = array(
            1=>array("tags_view", "tag_name"),
            2=>array("all_atrs_view", "o_name"),
        );
        if($this->key!='') {
            
   	        $querry = 'SELECT '.$descriptors_names_for_sql[$this->descriptor_id][1].
                      ' FROM nir.'.$descriptors_names_for_sql[$this->descriptor_id][0].
                      ' WHERE UPPER('.$descriptors_names_for_sql[$this->descriptor_id][1].') LIKE \'%'.
                      mb_strtoupper($this->key, 'utf-8').'%\' GROUP BY '.
                      $descriptors_names_for_sql[$this->descriptor_id][1];
        } else {
            
            $querry = 'SELECT tag_name FROM nir."tags_view" WHERE tag_name=""';
        }
        return $querry;
    }
    
    //Выполняет запрос на автодополнение
    public function getAutocompleteRequest() {
          
        $querry = $this->getAutocompleteSql();   
        $results= pg_query($this->link, $querry);
       	$output = '{ "query":"'. $this->key.'","suggestions":[';  
    	while ($row = pg_fetch_row($results)) {
    	$i++;
    		if($i == 1) {
    
    			$output .= '"'.$row[0].'"';
    			} else {
    				$output .= ',"'.$row[0].'"';
    			}
    	}
    	$output .= ']}';   
    	return $output;
    } 
    
           
    
}
