<?php

class DBase {
    var $host;
    var $port;
    var $db_name;
    var $user;
    var $password;
    var $link;
    var $error;
    var $result;

    function __construct()
    {
        $this->host = 'localhost';
        $this->port = '5432';
        $this->db_name = 'xgb_nir';
        $this->user = $_SESSION["AUTH"];
        $this->password = $_SESSION["PASSWORD"];
        
    }
    public function dbPostgresSQLConnect () {
        $this->link = pg_connect("host=$this->host port=$this->port dbname=$this->db_name user=$this->user password=$this->password");
        if ($this->link == false) {
            //$_SERVER['PHP_AUTH_USER']= '';
            $_SESSION["AUTH"] = '';
            header('WWW-Authenticate: Basic Realm="Login please"');
            //Users::auth_curuser();
            exit;
            //die("<< Ошибка соединения с БД >>");
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setDbName($db_name)
    {
        $this->db_name = $db_name;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getLink() {
        return $this->link;
    }

    public function showResult() {
        echo $this->result;
    }
}
