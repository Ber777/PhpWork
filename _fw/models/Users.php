<?php
class Users
{
    var $id = 0;
    var $login = '';
    var $name = '';

    var $profile_name = '';
    var $profile_settings = array();
    var $profile_id = 0;

    var $user_in_system = '';

    var $parid = 0;
    var $partype = 0;
    var $link;

    var $isAdmin = false,
        $isEditor = false,
        $isDirector = false,
        $isWorker = false,
        $isReader = false;

    var $rigth_par;

    public function __construct() {}

    public function canAddDb()
    {
        if ($this->isAdmin)
            return true;
        else
            return false;
    }

    public function canAddKz()
    {
        if ($this->isAdmin OR $this->isDirector)
            return true;
        else
            return false;
    }

    public function canAddCat()
    {
        //$this->get_Rigth($this->parid);
        if ($this->isAdmin OR $this->isEditor OR $this->isWorker)
            return true;
        else
            return false;
    }

    public function canAddDoc()
    {
        if ($this->isAdmin OR $this->isEditor OR $this->isWorker)
            return true;
        else
            return false;
    }

    public function canAddComment()
    {
        if ($this->isAdmin OR $this->isWorker)
            return true;
        else
            return false;
    }

    public function canAddTag()
    {
        if ($this->isAdmin OR $this->isEditor)
            return true;
        else
            return false;
    }

    public function canAddAttr()
    {
        if ($this->isAdmin OR $this->isEditor)
            return true;
        else
            return false;
    }

    public function canAddCatTemp()
    {
        if ($this->isAdmin OR $this->isEditor)
            return true;
        else
            return false;
    }

    public function canAddDocTemp()
    {
        if ($this->isAdmin OR $this->isEditor)
            return true;
        else
            return false;
    }

    public function canAddKzTemp()
    {
        if ($this->isAdmin OR $this->isDirector)
            return true;
        else
            return false;
    }

    public function canAddSearchTemp()
    {
        if ($this->isAdmin OR $this->isEditor OR $this->isWorker OR $this->isReader)
            return true;
        else
            return false;
    }
    
    public function canExport()
    {
        if ($this->isAdmin OR $this->isEditor OR $this->isWorker)
            return true;
        else
            return false;
    }    

    protected static $cu;

    public static function get_curuser()
    {
        if(!(isset(self::$cu)))
        {
            self::$cu = new Users();
            self::$cu->user_sys = $_SESSION["AUTH"];
            self::$cu->link = $GLOBALS['DATABASE']->getLink();
            //self::$cu->link = DATABASE::db_link;
            self::$cu->get_current_user();
        }
        return self::$cu;
    }

    public function setCurPlace($obj_id)
    {
        $this->parid = $obj_id;
        //$this->get_Rigth($obj_id);
       // $query = pg_query($this->link, "SELECT isreader , isworker , iseditor , isdirector , isadmin  from nir.get_access($obj_id, $this->id) ") ;
        //  ???????        ?? ??????? ???????? ??? ???????
       // return $this->get_Rigth($obj_id);
    }

    public function get_current_user()
    {
        //$user_sys1 = 'ostrikov@astral.bmstu';
	    //$user_sys1 = $_SERVER['REMOTE_USER'];
	    //echo $user_sys1;
        //if (strpos($user_sys1, '@')) {
        //    $user_sys = substr($user_sys1, 0, strpos($user_sys1, '@'));
        //}
        // $user_sys = "postgres";
        //$user_sys = "xgb_nir";
	//$user_sys = $_SERVER['PHP_AUTH_USER'];
        $user_sys = $_SESSION["AUTH"];
	//$user_sys = "xgb_nir";
        //$user_sys = $this->user_in_system;
        //echo "SELECT o_id, o_name, o_id_type, user_id, user_name, user_id_system, isadmin, iseditor, isworker, isreader  FROM nir.full_users_view  where user_id_system='$user_sys'";
        //$query = pg_query($link, "SELECT o_id, o_name, o_id_type, user_id, user_name, user_id_system, isadmin, iseditor, isworker, isreader  FROM nir.full_users_view  where user_id_system=current_user limit 1");
        $query = pg_query($this->link, "SELECT o_id, o_name, o_id_type, user_id, user_name, user_id_system, isadmin, iseditor, isworker, isreader, isdirector  FROM nir.full_users_view  where user_id_system='$user_sys' ");
        //echo "SELECT o_id, o_name, o_id_type, user_id, user_name, user_id_system, isadmin, iseditor, isworker, isreader  FROM nir.full_users_view  where user_id_system='$user_sys'";
        //echo pg_last_error();

        if ($query) {
            $data = pg_fetch_array($query);
            $this->id = $data['o_id'];
            $this->name = $data['user_name'];
            $this->login = $data['user_id_system'];
            $this->isAdmin = ($data['isadmin']=='t');
            $this->isEditor = ($data['iseditor']=='t');
            $this->isReader = ($data['isreader']=='t');
            $this->isWorker = ($data['isworker']=='t');
            $this->isDirector = ($data['isdirector']=='t');
        }
        return $this;
    }

    public static function auth_curuser()
    {
      session_start();
      if(!(isset($_SESSION["AUTH"])) || ($_SESSION["AUTH"]== ''))
      {
        if(isset($_SERVER['PHP_AUTH_USER']))
        { 
          //session_start();
          $_SESSION["AUTH"]  = $_SERVER['PHP_AUTH_USER'];
          $_SESSION["PASSWORD"]  = $_SERVER['PHP_AUTH_PW'];    
        }

      else{ 
        header('WWW-Authenticate: Basic Realm="Private"');
        exit;
      }
      }

      //$curuser_name = $_SERVER['PHP_AUTH_USER'];
      //$curuser_pass = $_SERVER['PHP_AUTH_PW'];
      //$curuser_name = $_SESSION["AUTH"];
      //$curuser_pass = $_SESSION["PASSWORD"];

      /*$conn = pg_connect("host=localhost dbname=xgb_nir user=$curuser_name password=$curuser_pass");

      if (!$conn) {
        echo "Ошибка подключения.\n";
        exit;//here 
      }

      $query = "SELECT user_name FROM nir.full_users_view  where user_id_system='$curuser_name' ";
      $result = pg_query($conn, $query);

      if (!$result) {
        echo "Ошибка запроса.\n";
        exit;
      }

      $data = pg_fetch_array($result, NULL, PGSQL_ASSOC);
      $this->name = $data['user_name'];

      return $this->name;*/
    }

    /*public static function logout()
    {
      $_SESSION["AUTH"] = '';
      header('WWW-Authenticate: Basic Realm="Login please"');
      exit;
    }*/

// ??????? ????????? ???????
    public function isOwner($obj)
    {
        //echo "SELECT nir.isowner( $obj, $this->id)";
        $query = pg_query($this->link, "SELECT nir.isowner( $obj, $this->id) ") ;
        $is = false;
        if ($query) {
            $data = pg_fetch_row($query);
            $is = ($data[0] == 't');
        }
        //echo " - ".$is.
        return $is;
    }

// ???????? ????? ???????????? ? ???????
    public function get_Rigth($obj_id)
    {
        $rigth = new Rights;
        $io =  $this->isOwner($obj_id) ;
        $type=0;
        $query = pg_query($this->link, "SELECT o_id_type from nir.nir_object where o_id=$obj_id ") ;
        $data = pg_fetch_row($query);
        if ($query && $data)
        {
            $type = $data[0];
        }
        $query = pg_query($this->link, "SELECT isreader , isworker , iseditor , isdirector , isadmin  from nir.get_access($obj_id, $this->id) ") ;
        if( $query )
        {
            $data = pg_fetch_row($query);
            if( $type == 13)
            {
                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = ( ($data[4] == 't') || $io);
                $rigth->drop = ( ($data[4] == 't') || $io);
                $rigth->grant = ( ($data[4] == 't') || $io);
                $rigth->canaddcat = (($data[2] == 't') || $io);
                $rigth->canadddoc = (($data[1] == 't') || $io); 
            }
            else if( $type == 1 ) {

                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = (($data[3] == 't') || ($data[4] == 't') || $io);
                //$rigth->update = ( ($data[4] == 't') || $io);
                $rigth->drop = (($data[3] == 't') || ($data[4] == 't') || $io);
                //$rigth->drop = ( ($data[4] == 't') || $io);
                $rigth->grant = (($data[3] == 't') || ($data[4] == 't') || $io);
                //$rigth->grant = ( ($data[4] == 't') || $io);
                $rigth->canaddcat = (($data[2] == 't') || $io); 
                $rigth->canadddoc = (($data[1] == 't') || $io);
                $rigth->canaddcomment = (($data[1] == 't') || $io);
            }
            else if( $type == 4  )         {
                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = ( ($data[3] == 't') || ($data[2] == 't') || $io); 
                //$rigth->update = ( ($data[3] == 't') || ($data[2] == 't') || $io);
                //$rigth->drop = ( ($data[2] == 't') || ($data[3] == 't') || $io); 
                $rigth->drop = ( ($data[2] == 't') || ($data[3] == 't') || $io);
                //$rigth->grant = ( ($data[4] == 't') || $io );
                $rigth->grant = (($data[3] == 't') || ($data[4] == 't') || $io);  
                $rigth->canaddcat = (($data[2] == 't') || $io);
                $rigth->canadddoc = (($data[1] == 't') || $io);   
                             
            }
            else if( $type == 5  )            {

                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = ( ($data[3] == 't') || ($data[2] == 't') || $io);
                $rigth->drop = ( ($data[2] == 't') || ($data[3] == 't') || $io);
                $rigth->grant = (($data[3] == 't') || ($data[4] == 't') || $io);
               
            }
            else if( $type == 10 )   {

                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = ( ($data[3] == 't') || $io);
                $rigth->drop = ( ($data[3] == 't') || $io);
                $rigth->grant = ( ($data[3] == 't') );
                
            }
            else {
                $rigth->read = ( ($data[0] == 't') || $io);
                $rigth->update = ( ($data[4] == 't') || $io);
                $rigth->drop = ( ($data[4] == 't') || ($io ) );
                $rigth->grant = ( ($data[4] == 't') );
            }
        }
        return $rigth;
    }

    public function add_profile () {
        $user_id = $this->id;
        $name = "Профиль пользователя " . $this->profile_name;
        $query = pg_query($this->link, "SELECT * FROM nir.add_profile($name, $user_id)");
        $result = pg_fetch_array($query);
        return $result[0];

    }

    public function set_attr_profile () {

        $user_id = $this->id;
        $settings_row = "ARRAY[";
        $i = 0;
        foreach ($this->profile_settings as $name => $value) {
            if ($i == 0) {
                $settings_row .= "cast(( 2, '" . $name . "', '" . $value . "') AS nir.atrtype)";
                $i = 1;
            } else {
                $settings_row .= ", cast(( 2, '" . $name . "', '" . $value . "') AS nir.atrtype)";
            }
        }
        $settings_row .= "]::nir.atrtype[]";

        $query = pg_query($this->link, "SELECT nir.set_atrs_profile($settings_row, $user_id)");
        $result = pg_fetch_array($query);

        return $result[0];
    }

    public function get_attr_profile () {
        $profile = $this->get_user_profile();
        $settings = array();
        $id = $profile['id'];
        $query = pg_query($this->link, "SELECT * FROM nir.atrs_view_2 where obj_id =".$id);
        while($data = pg_fetch_array($query))
        {
            $settings[$data['atr_name']] = $data['atr_value'];
        }
        $this->profile_settings = $settings;
        return $this->profile_settings;
    }

    public function get_all_profiles () {
        $query = pg_query($this->link, "SELECT * FROM nir.user_profile_view");
        $profiles = array();
        while ($data = pg_fetch_assoc($query)) {
            $profiles[] = $data;
        }
        return $profiles;
    }

    public function get_user_profile () {
        $id_user = $this->id;
        $query = pg_query($this->link, "SELECT * FROM nir.user_profile_view WHERE user_id=".$id_user);
        while($data = pg_fetch_array($query))
        {
            $profile = array("id" => $data['profile_id'], "name" => $data['profile_name'], "user_id" => $data['user_id']);
        }
        $this->profile_id = $profile['id'];
        return $profile;
    }

}
