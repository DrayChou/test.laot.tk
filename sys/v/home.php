<?php if (!defined('S')) show_error(); ?>
<div class='row'>
    <div class='span7'>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>序号[ID]</th>
                    <th>名称[Name]</th>
                    <th>授权[Oauth]</th>
                    <th>状态[Static]</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($oauth_list as $k => $v):?>
                <tr id='tr_<?=$k?>'>
                    <td>
                        <?=$k?>
                        <input type='hidden' name='id' value='<?=$k?>'>
                        <input type='hidden' name='name' value='<?=$v['name']?>'>
                        <input type='hidden' name='description' value='<?=$v['description']?>'>
                        <input type='hidden' name='website' value='<?=$v['website']?>'>
                        <input type='hidden' name='key' value='<?=$v['key']?>'>
                        <input type='hidden' name='secret' value='<?=$v['secret']?>'>
                    </td>
                    <td><?=$v['name']?></td>
                    <td>
                        <?php if(empty($v['oauth_token'])):?>
                        <a class="btn" href="<?=BASE_URL?>oauth">授权登陆</a>
                        <?php else:?>
                        已授权
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if($k == $oauth_used):?>
                        使用中
                        <?php else:?>
                        <a class="btn" href="<?=BASE_URL?>oauth?oi=<?=$k?>&os=1">使用这个</a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class='span5 bs-docs-sidebar'>
        <form action="<?=BASE_URL?>home/save" method="post" onsubmit='return check();'>
            <legend>添加[New]</legend>
            <div class="control-group">
                <div class="controls">
                    <input class="span2" type="text" name='new_id' placeholder="序号[ID] * 字母+数字">
                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="span2" type="text" name='new_name' placeholder="名称[Name]">
                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="span3" type="text" name='new_description' placeholder="简介[Description]">
                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="span3" type="text" name='new_website' placeholder="链接[Website]">
                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="span3" type="text" name='new_key' placeholder="密钥[Consumer key] *">
                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="span3" type="text" name='new_secret' placeholder="密令[Consumer secret] *">
                    <span class="help-inline"></span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">保存[Save]</button>
            <button type="reset" class="btn">重置[Reset]</button>
        </form>
    </div>
</div>
<script type="text/javascript">
function check(){
    var submit = true;
    var rule = <?=json_encode($edit_check)?>;
    $(rule).each(function(){
        var t = $('input[name="'+this[0]+'"]');
        if(eval(this[1]).test(t.val())){
            t.parents('div.control-group').removeClass('error warning info success').addClass('success');
            t.next().html('');
        } else {
            t.parents('div.control-group').removeClass('error warning info success').addClass(this[2]);
            t.next().html(this[3]);
            if(this[2] == 'error'){
                submit = false;
            }
        }
    });

    return submit;
}

$(document).ready(function() {
    $('tbody tr').click(function(){
        $('form legend').html('编辑[Edit]');
        var id = this.id.split('_',2)[1];
        $('tbody tr td input[type="hidden"]').each(function(){
            $('form input[name="new_'+this.name+'"]').val(this.value);
        });
        $('form div.control-group').removeClass('error warning info success');
        $('form span.help-inline').html('');
    });

    $('button[type="reset"]').click(function(){
        $('form legend').html('添加[New]');
        $('form div.control-group').removeClass('error warning info success');
        $('form span.help-inline').html('');
    });
});

</script>