<?php 

function getSql( $data)
{
      $str = "select o_id, o_name from nir.nir_object where o_id_type=5 and ( ";
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
 	      foreach ($data2 as $item)
		{
                  if( $first )
                     {
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
 echo $str;
        return $str;
}        
 ?>