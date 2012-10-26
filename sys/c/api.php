<?php
if (!defined('S')) show_error(); 
t('API管理');

class api {
    public function index(){
        global $_config;
        write_log(0,'_SESSION:'.print_r($_SESSION,true));
        
        if(isset($_SESSION['user_id'])){
            $parm = array(
                'oauth_used' => $_SESSION['oauths']['used'],
                'oauth_list' => $_SESSION['oauths']['list'],
                'edit_check' => $_config['edit_check'],
            );

            view('api',$parm);
        } else {
            view('index');
        }
    }

    public function save(){
        global $_config;
        $result = array(
            's' => 0,
            'i' => '设置成功',
        );

        if(empty($_SESSION['screen_name'])){
            show_error('出错了','请先登录');
        }

        $edit_check = $_config['edit_check'];
        foreach ($edit_check as $v) {
            if(!isset($_POST[$v[0]]) && $v[2] == 'error'){
                if (preg_match($v[1], $_POST[$v[0]]) == 0) {
                    show_error('出错了','变量 ',$v[3],' 异常');
                }
            }
        }

        $oauth_id = isset($_POST['new_id'])?$_POST['new_id']:'';
        if(empty($oauth_id)){
            show_error('出错了','序号异常');
        }

        $new_key = isset($_POST['new_key'])?$_POST['new_key']:'';
        if(empty($new_key)){
            show_error('出错了','密钥异常');
        }

        $new_secret = isset($_POST['new_secret'])?$_POST['new_secret']:'';
        if(empty($new_secret)){
            show_error('出错了','密令异常');
        }

        $_SESSION['oauths']['list'][$oauth_id] = array(
            'name' => isset($_POST['new_name'])?$_POST['new_name']:'',
            'description' => isset($_POST['new_description'])?$_POST['new_description']:'',
            'website' => isset($_POST['new_website'])?$_POST['new_website']:'',
            'key' => $new_key,
            'secret' => $new_secret,
            'oauth_token' => '',
            'oauth_token_secret' => '',
        );

        write_log(0,'_SESSION:'.print_r($_SESSION,true));

        if(save_config($_SESSION)){
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
            echo "<script Language=Javascript>";
            echo "  alert(\"设置成功\");";
            echo " window.location='" . BASE_URL . "api';";
            echo "</script>";
        }else{
            echo "保存登陆信息失败.";
        }
    }
}