<?php
if (!defined('S')) show_error(); 
t('授权');

class oauth{
    public function index(){
        write_log(0,'_POST:'.print_r($_POST,true));
        write_log(0,'_GET:'.print_r($_GET,true));
        write_log(0,'_COOKIE:'.print_r($_COOKIE,true));
        write_log(0,'session_id:'.print_r(session_id(),true));

        $user_config = load_config();
        write_log(0,'user_config:'.print_r($user_config,true));
        $oauth_id = isset($_GET['oi'])?$_GET['oi']:'base';
        write_log(0,'oauth_id:'.print_r($oauth_id,true));
        if(!isset($user_config['oauths']['list'][$oauth_id])){
            die('错误的授权ID:'.$oauth_id);
        }
        $oauth = $user_config['oauths']['list'][$oauth_id];
        write_log(0,'oauth:'.print_r($oauth,true));

        if(isset($_SESSION['user_id']) && isset($_GET['os'])){
            $user_config['oauths']['used'] = $oauth_id;
            if(save_config($user_config)){
                echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
                echo "<script Language=Javascript>";
                echo "  alert(\"设置成功\");";
                echo " window.location='" . $url . "';";
                echo "</script>";
            }else{
                echo "保存信息失败.";
            }
        }else{
            if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
                write_log(0,'session_id:'.print_r(session_id(),true));
                write_log(0,'_SESSION:'.print_r($_SESSION,true));

                $connection = new TwitterOAuth($oauth['key'], $oauth['secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
                $access_token = $connection->getAccessToken($_GET['oauth_verifier']);
                write_log(0,'access_token:'.print_r($access_token,true));

                if($connection->http_code == 200){
                    $user_id = $access_token['user_id'];
                    $screen_name = $access_token['screen_name'];
                    $user_config = array_merge($user_config,load_config($screen_name,$user_id));
                    $user_config['user_id'] = $user_id;
                    $user_config['screen_name'] = $screen_name;
                    $user_config['oauths']['list'][$oauth_id]['oauth_token'] = $access_token['oauth_token'];
                    $user_config['oauths']['list'][$oauth_id]['oauth_token_secret'] = $access_token['oauth_token_secret'];
                    $user_config['user_info'] = $connection->get('account/verify_credentials');

                    if(isset($_GET['os']) || count($user_config['oauths']['list']) == 1){
                        $user_config['oauths']['used'] = $oauth_id;
                    }

                    $twitter_str = 'return '.var_export($user_config,true).';';
                    write_log(0,'twitter_str:'.print_r($twitter_str,true));

                    if(save_config($user_config)){
                        echo "<pre>";
                        echo print_r($user_config,true);
                        echo "</pre>";

                        $_SESSION = array_merge($_SESSION,$user_config);
                        unset($_SESSION['oauth_token']);
                        unset($_SESSION['oauth_token_secret']);

                        header('Location: '.BASE_URL);
                    }else{
                        echo "保存登陆信息失败.";
                    }
                }
                else {
                    echo "错误序号: ".$connection->http_code."";
                }
            } else {
                $connection = new TwitterOAuth($oauth['key'], $oauth['secret']);
                $request_token = $connection->getRequestToken(BASE_URL.'oauth?oi='.$oauth_id);

                write_log(0,'session_id:'.print_r(session_id(),true));
                write_log(0,'request_token:'.print_r($request_token,true));

                /* Save request token to session */
                $_SESSION['oauth_token'] = $request_token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
                session_write_close();

                write_log(0,'session_id:'.print_r(session_id(),true));
                write_log(0,'_SESSION:'.print_r($_SESSION,true));
                write_log(0,'http_code:'.print_r($connection->http_code,true));

                switch ($connection->http_code) {
                    case 200:{
                        /* Build authorize URL */
                        $url = $connection->getAuthorizeURL($_SESSION['oauth_token'],FALSE);

                        write_log(0,'url:'.print_r($url,true));
                        // if ($_GET['type']==1 || !isset($_GET['type'])) {
                            header('HTTP/1.1 302 Found');
                            header('Status: 302 Found');
                            header('Location: ' . $url); 
                        // } else {
                        //     // encode user and password for decode.
                        //     $encUser = base64_encode($_POST['username']);
                        //     $encPass = base64_encode($_POST['password']);
                        //     header('HTTP/1.1 302 Found');
                        //     header('Status: 302 Found');
                        //     header('Location: oauth_proxy.php?u=' . $encUser . '&p=' . $encPass . '&g=' . urlencode($url));
                        // }
                    }
                    break;
                    default:{
                        if($connection->http_code==0){
                            echo "Could not connect to Twitter. Refresh the page or try again later.<br/>Error code:".$connection->http_code.".<br/>Don't report bugs or issues if you got this error code. Twitter is not accessible on this host. Perhaps the hosting company blocked Twitter.";
                        }
                    }
                    break;
                }
            }
        }
    }
}