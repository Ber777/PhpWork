<?php

class profileController extends Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->name_object = 'profile';
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->arrayCSS = array(
            '/pages/profile/profile.css'
        );
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function index() {
        $this->settings();
    }

    public function settings() {

        $this->setValuesHelper(array(
            'name_object' => 'profile',
            'fields' => $this->user_info->profile_settings,
            'id_current_object' => $this->user_info->profile_id
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Настройки профиля',
            $this->data
        );
    }

    public function ajaxSetProfile($id_profile) {
        if ($this->isAjax()) {
            if ($id_profile) {
                $this->user_info->profile_settings = $this->getAllPOST();
                $this->user_info->set_attr_profile();

                $this->setObjectAjaxResponse('string', 1);

            } else {
                $this->setErrorResponse('404', 'Не указан ID профиля');
            }

        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }

    }

    public function ajaxListUsers() {
        if ($this->isAjax()) {
            $list_roles = PageRole::getUserRoleName();
            $list_users = PageRole::getAllUsers();

            $this->setValuesHelper(array(
                'array_users' => $list_users,
                'array_roles' => $list_roles
            ));

            $this->setObjectAjaxResponse('html', 'TableUsers');

        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAuth() {
        if ($this->isAjax()) {
            $login = $this->getPOST('login');
            $password = $this->getPOST('password');

	    $host = 'localhost';
 	    $port = '5432';
	    $db   = 'xgb_nir';
	    $link = pg_connect("host=$host port=$port dbname=$db user=$login password=$password");
	    if ($link == true) {
                $this->user_info->user_in_system = $login;
                $this->user_info->get_current_user();
                if ($this->user_info->id) {
                    $this->setUserSession($this->user_info->user_in_system);
                    $this->setObjectAjaxResponse('string', 1);
                } else {
                    $this->setObjectAjaxResponse('string', 2);
                }
	    } else {
                $this->setObjectAjaxResponse('string', 3);
	    }

	    /*
            if ($login == $password) {
                $this->user_info->user_in_system = $login;
                $this->user_info->get_current_user();
                if ($this->user_info->id) {
                    $this->setUserSession($this->user_info->user_in_system);
                    $this->setObjectAjaxResponse('string', 1);
                } else {
                    $this->setObjectAjaxResponse('string', 2);
                }
            } else {
                $this->setObjectAjaxResponse('string', 3);
            }
	    */

        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    /*public function logout()
    {
      echo "LOGOUT";
      //Users::logout();
    }*/


}
