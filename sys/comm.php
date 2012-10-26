<?php

//读取用户设置
function load_config($user_id = 0) {
    if(empty($user_id)){
        if(file_exists(DATA_PATH.'_base.php')){
            return require(DATA_PATH.'_base.php');
        }
    }else{
        if(file_exists(DATA_PATH.'users/'.$user_id.'.php')){
            return require(DATA_PATH.'users/'.$user_id.'.php');
        }

        $old_tokens = glob(DATA_PATH.'users/'.$user_id.'_*'.'.php');
        if(!empty($old_tokens)){
            foreach($old_tokens as $file){
                return require($file);
            }
        }
    }
    return array();
}

//设置用户设置
function save_config($user_config) {
    if(!empty($user_config)){
        $config_str = "<?php\nreturn ".var_export($user_config,true).';';
        if( $user_id = $user_config['user_id'] ){
            return file_put_contents(DATA_PATH.'users/'.$user_id.'.php', $config_str);
        }
    }

    return false;
}

// 写入日志
function write_log($level = 0 ,$content = 'none') {
    $content = date("Y-m-d H:i:s")."\n".$_SERVER['REQUEST_URI']."\n".$content."\n";
    file_put_contents(DATA_PATH.'log/'.$level.'-'.date('Y-m-d').'.log', $content , FILE_APPEND );
}

/* 调用 view 文件
* function view($view,$param = array(),$cache = FALSE)
* $view 是模板文件相对 app/v/ 目录的地址，地址应去除 .php 文件后缀
* $param 数组中的变量会传递给模板文件
* $cache = TRUE 时，不像浏览器输出结果，而是以 string 的形式 return
*/
function view($view,$param = array(),$cache = FALSE)
{
    if(!empty($param)){
        extract($param);
    }
    ob_start();
    if(is_file(SYS_PATH.'v/'.$view.'.php')) {
        require SYS_PATH.'v/'.$view.'.php';
    } else {
        echo 'view '.$view.' desn\'t exsit';
        return false;
    }
    // Return the file data if requested
    if ($cache === TRUE) {
        $buffer = ob_get_contents();
        @ob_end_clean();
        return $buffer;
    }
}

//echo ' 显示错误';
function show_error($title='404', $info='We could not find the link/file you were looking for.', $url=BASE_URL, $url_title='返回首页') {
    $parm = array(
        'title' => $title,
        'info' => $info,
        'url' => $url,
        'url_title' => $url_title,
    );

    header("HTTP/1.1 404 Not Found");
    view('header');
    view('404',$parm);
    view('footer');
    exit();
}
