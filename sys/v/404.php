<?php t('Page not found');
header('HTTP/1.0 404 Not Found'); ?>
<style>
.container {
    text-align: center;
}
.masthead {
    margin-bottom: 0;
    padding: 70px 0 80px;
}
.masthead h1 {
    font-size: 120px;
    letter-spacing: -2px;
    line-height: 1;
}
.masthead p {
    font-size: 40px;
    font-weight: 200;
    line-height: 1.25;
}
.masthead-links {
    list-style: none outside none;
    margin: 0;
}
.masthead-links li {
    display: inline;
    padding: 0 10px;
}
</style>
<div class='masthead'>
    <h1><?=$title?></h1>
    <p><?=$info?></p>
    <p><a class="btn btn-primary btn-large" href="<?=$url?>"><?=$url_title?></a></p>
</div>