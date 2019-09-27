<?php

class profileHelper extends Helper
{
    var $array_users;
    var $array_roles;
    var $fields = array(
        
    );

    public function PanelSettings() {
        $this->drawBlock('forms', 'set_theme_profile');
    }

    public function TableUsers() {
        $this->drawBlock('popup-content', 'list_users');
    }


}