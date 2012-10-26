<?php
if (!defined('S')) show_error(); 
t('首页');

class home {
    public function index(){
        global $_config;
        write_log(0,'_SESSION:'.print_r($_SESSION,true));

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
}