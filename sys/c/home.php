<?php
if (!defined('S')) show_error(); 
t('');

class home {
    public function index(){
        global $_config;
        if(isset($_SESSION['user_id'])){
            $parm = array(
                'oauth_used' => $_SESSION['oauths']['used'],
                'oauth_list' => $_SESSION['oauths']['list'],
                'edit_check' => $_config['edit_check'],
            );

            view('home',$parm);
        } else {
            view('index');
        }
    }

    public function logout(){
        session_unset();
        header('Location: '.BASE_URL);
    }

    public function save(){
        global $_config;
        $result = array(
            's' => 0,
            'i' => '设置成功',
        );

        $edit_check = $_config['edit_check'];
        foreach ($edit_check as $v) {
            if(!isset($_POST[$v[0]]) && $v[2] == 'error'){
                
            }
        }
        
        var_dump($edit_check);
    }
}