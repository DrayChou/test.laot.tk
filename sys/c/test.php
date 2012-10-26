<?php
if (!defined('S')) show_error(); 
t('查看那些id没用过');

class test {
    public function index(){
        echo <<<END
<div id='result'>
</div>
<script type="text/javascript">
    var words = ['','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','0','_'];
    var temp_array = [];
    var da = 0;
    var db = 0;
    var dc = 0;
    var dd = 0;
    var de = 0;
    var len = words.length - 1;

    setTimeout(lookup, 1000);
    setTimeout(show, 2000);

    function lookup(){
        var done = 0;

        for (var a = da; a <= len; a++) {
            for (var b = db; b <= len; b++) {
                for (var c = dc; c <= len; c++) {
                    for (var d = dd; d <= len; d++) {
                        for (var e = de; e <= len; e++) {
                            var temp = words[a]+words[b]+words[c]+words[d]+words[e];
                            if(temp.length>0){
                                temp_array[temp_array.length] = temp;
                                if(temp_array.length>=99){
                                    var sn = temp_array.join(',');
                                    $.post('/test/lookup',{a:a,b:b,c:c,d:d,e:e,sn:sn},
                                        function(re){
                                            var html = "";
                                            if(re.state == true){
                                                html += '<div class="un_used"><table class="table table-bordered"><tbody>';
                                                for(var i=0;i<re.un_used.length;i++){
                                                    html += '<tr class="undo" id="un_used_'+re.un_used[i]+'"><td class="un_used_id">'+i+'</td><td class="un_used_sn">'+re.un_used[i]+'</td><td class="un_used_rs"></td></tr>';
                                                }
                                                html += '</tbody></table></div>';
                                            }else{
                                               html = re.info;
                                            }
                                            $('#result').append('<pre>查询：'+sn+'<br/>未被使用的结果：'+html+'</pre>'); 
                                        }
                                    ,'json');
                                    temp_array = [];
                                    da = a;
                                    db = b;
                                    dc = c;
                                    dd = d;
                                    de = e;

                                    done = 1;
                                    break;
                                }
                            }
                        }
                        if(done == 1){
                            break;
                        }
                    }
                    if(done == 1){
                        break;
                    }
                }
                if(done == 1){
                    break;
                }
            }
            if(done == 1){
                break;
            }
        }

        if(temp_array.length>0){
            var sn = temp_array.join(',');
            $.post('/test/lookup',{a:a,b:b,c:c,d:d,sn:sn},
                function(re){
                    var html = "";
                    if(re.state == true){
                        html += '<div class="un_used"><table class="table table-bordered"><tbody>';
                        for(var i=0;i<re.un_used.length;i++){
                            html += '<tr class="undo" id="un_used_'+re.un_used[i]+'"><td class="un_used_id">'+i+'</td><td class="un_used_sn">'+re.un_used[i]+'</td><td class="un_used_rs"></td></tr>';
                        }
                        html += '</tbody></table></div>';
                    }else{
                       html = re.info;
                    }
                    $('#result').append('<pre>查询：'+sn+'<br/>未被使用的结果：'+html+'</pre>'); 
                }
            ,'json');
            temp_array = [];
            da = a;
            db = b;
            dc = c;
            dd = d;
            de = a;
        }

        //setTimeout(lookup, 5000);
    }

    function show(){
        $('div.un_used tr.undo').each(function(){
            var sn = $(this).children('td.un_used_sn').text();
            $.post('/test/show',{sn:sn},
                function(re){
                    $(this).children('td.un_used_rs').html(re.info);
                    $(this).attr('class','done');
                }
            ,'json');
        });
        setTimeout(show, 2000);
    }

</script>
END;
    }

    public function lookup(){
        if(isset($_SESSION['user_id'])){
            $oauth = $_SESSION['oauths']['list'][$_SESSION['oauths']['used']];
            $connection = new TwitterOAuth($oauth['key'], $oauth['secret'], $oauth['oauth_token'], $oauth['oauth_token_secret']);

            $sn = isset($_POST['sn'])?$_POST['sn']:'';
            if(empty($sn)){
                print(json_encode(array('state'=>false,'info'=>'怎么没有传送用户名串过来？')));
                die(header("Status: 200"));
            }

            $un_used = array();
            $res = $connection->post('users/lookup',array('screen_name' => $sn));
            write_log(0,'screen_name:'.print_r($sn,true));
            //write_log(0,'users/lookup:'.print_r($res,true));

            if(isset($res['error']) || isset($res['errors'])){
                $info = isset($res['error'])?$res['error']:(isset($res['errors'])?$res['errors'][0]['message']:'出错了！');
                print(json_encode(array('state'=>false,'info'=>$info)));
                die(header("Status: 200"));
            }

            $sn_array = explode(',', $sn);
            $used = array();
            foreach ($res as $v) {
                $used[] = strtolower($v['screen_name']);
            }
            $un_used = array_diff($sn_array, $used, array(''));

            write_log(0,'used:'.print_r($used,true));
            write_log(0,'un_used:'.print_r($un_used,true));

            if(empty($un_used)){
                print(json_encode(array('state'=>false,'info'=>'没有找到可以用的用户名！')));
            }else{
                print(json_encode(array('state'=>true,'info'=>'查询成功','un_used'=>array_values($un_used))));
            }
        }else{
            print(json_encode(array('state'=>false,'info'=>'请先取得授权')));
        }
        die(header("Status: 200"));
    }

    public function show(){
        if(isset($_SESSION['user_id'])){
            $oauth = $_SESSION['oauths']['list'][$_SESSION['oauths']['used']];
            $connection = new TwitterOAuth($oauth['key'], $oauth['secret'], $oauth['oauth_token'], $oauth['oauth_token_secret']);

            $sn = isset($_POST['sn'])?$_POST['sn']:'';
            if(empty($sn)){
                print(json_encode(array('state'=>false,'info'=>'怎么没有传送用户名串过来？')));
                die(header("Status: 200"));
            }

            $res = $connection->get('users/show',array('screen_name' => $sn));
            if(isset($res['error']) || isset($res['errors'])){
                $info = isset($res['error'])?$res['error']:(isset($res['errors'])?$res['errors'][0]['message']:'出错了！');
                print(json_encode(array('state'=>false,'info'=>$info)));

            }

            print(json_encode(array('state'=>true,'info'=>'可用')));
        }else{
            print(json_encode(array('state'=>false,'info'=>'请先取得授权')));
        }
        die(header("Status: 200"));
    }

    public function t1(){
        if(isset($_SESSION['user_id'])){
            $oauth = $_SESSION['oauths']['list'][$_SESSION['oauths']['used']];
            $connection = new TwitterOAuth($oauth['key'], $oauth['secret'], $oauth['oauth_token'], $oauth['oauth_token_secret']);

            $res = $connection->get('users/show',array('screen_name' => 'We_Get'));
            echo "<pre>";
            echo print_r($res,true);
            echo "</pre>";

            $res = $connection->get('users/show',array('screen_name' => 'We_Get00000000000000000000000000'));
            echo "<pre>";
            echo print_r($res,true);
            echo "</pre>";

            $res = $connection->get('users/show',array('screen_name' => 'h3'));
            echo "<pre>";
            echo print_r($res,true);
            echo "</pre>";

            $res = $connection->post('users/lookup',array('screen_name' => 'We_Get,h3'));
            echo "<pre>";
            echo print_r($res,true);
            echo "</pre>";
        }
    }
}

?>
