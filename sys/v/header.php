<?php if (!defined('S')) show_error(); ?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex,nofollow"></head>
    <title>不良大叔的超级测试机 <?=t()?></title>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        img.header_img {
            margin-right: 5px;
            top: 10px;
            left: 10px;
            width: 20px;
            height: 20px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>static/bootstrap/css/bootstrap-responsive.min.css" />
    <script type="text/javascript" src='<?=BASE_URL?>static/js/jquery-1.8.1.min.js' ></script>
    <script type="text/javascript" src='<?=BASE_URL?>static/bootstrap/js/bootstrap.min.js' ></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="<?=BASE_URL?>">不良大叔的超级测试机</a>
                <div class="nav-collapse collapse">
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <ul class="nav pull-right">
                        <li>
                            <a href="<?=BASE_URL?>">你好，<img src="<?php echo $_SESSION['user_info']['profile_image_url_https']; ?>" alt="<?php echo $_SESSION['screen_name']; ?>" class="header_img" title="<?php echo $_SESSION['screen_name']; ?>"><?php echo $_SESSION['screen_name']; ?></a>
                        </li>
                        <li>
                            <a href="<?=BASE_URL?>home/logout">退出登陆</a>
                        </li>
                    </ul>
                    <?php else:?>
                    <form class="navbar-form pull-right">
                        <a class="btn" href="<?=BASE_URL?>oauth">授权登陆</a>
                    </form>
                    <?php endif;?>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>

    <div class="container">