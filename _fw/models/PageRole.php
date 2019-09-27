<?php

class PageRole {
    
   // var $id_user;
   // var $id_obj;
   // var $arr_rightss=array();
    
   // public static function getUsers($id_obj) {
     //   $query = "SELECT id_user, login_user FROM nir.getusers_access($id_obj)";
       // $a_query = pg_query($query);
       // $users = array();
       // while ($data = pg_fetch_assoc($a_query)) {
        //    $users[$data['id_user']] = $data['login_user'];
       // }
       // return $users;
    //}

    public static function getAllUsers() {
        $query = "SELECT user_id_object, user_name FROM nir.getusers() ORDER BY user_name";
        $a_query = pg_query($query);
        $Allusers = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $Allusers[$data['user_id_object']] = $data['user_name'];
        }
        return $Allusers;
    }

    public static function getUserRoleMain() {
        $query = "SELECT * FROM nir.getuserrole()";
        $a_query = pg_query($query);
        $roles = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $roles[$data['user_id']] = $data['role_id'];
        }
        return $roles;
        
    }
    public static function getUserRoleMain2() {
        $query = "SELECT * FROM nir.getuserrole()";
        $a_query = pg_query($query);
        $roles = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $roles[$data['user_id']][] = $data['r_info'];
        }
        return $roles;
    }

    public static function getUserRoleName() {
        $query = "SELECT * FROM nir.getuserrole()";
        $a_query = pg_query($query);
        $roles = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $roles[$data['user_id']][] = $data['user_role_id'];
        }
        return $roles;
    }


    public static function getRoles($id_obj) {
        $query = "SELECT r_id, r_name, r_code, r_info FROM nir.getroles_access($id_obj)";
        $a_query = pg_query($query);
        $roles = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $roles[$data['r_code']] = array($data['r_name'],$data['r_info']);
        }
        return $roles;
        
    }

    public static function getUserRoles($id_obj, $id_subj) {
        $query = "SELECT get_access_mask_2 FROM nir.get_access_mask_2($id_obj, $id_subj)";
        $a_query = pg_query($query);
        $data = pg_fetch_assoc($a_query);
        //var_dump($data['get_access_mask_2']);
        //die();
        return $data['get_access_mask_2'];
    }
    
     public static function setUserRoles($arr_rightss) {
        $i = 0;
		$array_rights = "ARRAY[";
		foreach($arr_rightss as $right)
		{
			if($i == 0)
			{
				$array_rights .= "cast((".$right['obj'].", ".$right['user'].", '".$right['bit_map']."') AS nir.rightsss_of_access)";
				$i = 1;
			}
			else
			{	
				$array_rights .= ", cast((".$right['obj'].", ".$right['user'].", '".$right['bit_map']."') AS nir.rightsss_of_access)";
			}
		}
		$array_rights .= "]";
        $query = "SELECT * FROM nir.setrightsofusertoobj($array_rights)";
        //var_dump($query);
        $a_query = pg_query($query);
    }
    
    
    public static function delete($id_obj,$USERS) {
        $i = 0;
        $delUser = "ARRAY[";
		foreach($USERS as $user)
		{
			if($i == 0)
			{
				$delUser .= $user;
				$i = 1;
			}
			else
			{	
				$delUser .= ", ".$user;
			}
		}
		$delUser .= "]";
        $query = "SELECT * FROM nir.delete_right_of_access($id_obj, $delUser)";
        $a_query = pg_query($query);
    }
    
    
    public static function getUserNameById($id_us) {
        $query = "select * from nir.getusersByid($id_us)";
        $a_query = pg_query($query);
        $arr=array();
        $data = pg_fetch_assoc($a_query);
        $arr[$data['user_name']][]=$data['user_id_system'];
        return $arr;
    }
    
    public static function getGroupNameById($id_group) {
        $query = "select * from nir.getgroupbyid($id_group)";
        $a_query = pg_query($query);
        $data = pg_fetch_assoc($a_query);
        return $data['group_name'];
    }
    

}



