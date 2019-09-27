<?php

class Message {

    var $text; //сообщение
    var $id; //id
    var $id_user; // id user
    var $id_kz; //id карты знаний сообщ
    var $date; //дата
    var $name_user; //имя юзера

    public function getMessage() {
        $query = "SELECT o_id, kz_id, txt, user_id, user_name, td FROM nir.all_kzcomments_view where kz_id=" . $this->id_kz . "order by td";
        $a_query = pg_query($query);
        $messages = array();
        while ($data = pg_fetch_assoc($a_query)) {
            $messages[] = $data;
        }
        return $messages;
    }

    public function setMessage() {
        $query = "SELECT nir.addkzcomment(" . $this->id_kz . ", '" . $this->text . "',  '" . $this->name_user . "',  '" . $this->date . "')";
        $result = pg_query($query);
        $result = pg_fetch_array($result);
        if ($result[0] <= 0) {
            $error = pg_last_error();
            return $error;
        } else {
            return $result[0];
        }
    }

    public function delMessage() {
        $query = "SELECT * FROM nir.dropcomment($this->id)";
        $result = pg_query($query);
        $result = pg_fetch_array($result);
        return $result[0];
    }

    public function editMessage() {
        $query = "SELECT nir.edit_kzcomment(" . $this->id . ", '" . $this->text . "' )";
        $result = pg_query($query);
        $result = pg_fetch_array($result);
        if ($result[0] <= 0) {
            $error = pg_last_error();
            return $error;
        } else {
            return $result[0];
        }
    }

}



