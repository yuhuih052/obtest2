<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:69:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\login\login.html";i:1596095143;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>OneBase开源架构|PHP开源系统</title>
    <meta name="keywords" content="OneBase,PHP开源系统,ThinkPHP5,TP5,PHP框架,PHP源码"/>
    <meta name="description" content="一款基于ThinkPHP5研发的开源免费基础架构，基于OneBase可以快速的研发各类应用。" />
    <link href="__STATIC__/module/common/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="__STATIC__/module/common/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="__STATIC__/module/index/css/docs.css" rel="stylesheet">
    <link href="__STATIC__/module/index/css/onebase.css" rel="stylesheet">
    
    <script type="text/javascript" src="__STATIC__/module/common/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/common/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/index/js/common.js"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo url('index/home'); ?>">OneBase</a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                <ul class="nav">
                        <li>
                            <a href="<?php echo url('index/home'); ?>">首页</a>
                        </li>
                        <li>
                            <a href="<?php echo url('index/index'); ?>">新闻公告</a>
                        </li>

                        <?php if(\think\Request::instance()->session('user_id2') != null): ?>
                          <li><a href="<?php echo url('user/index',['id'=> \think\Request::instance()->session('user_id2')]); ?>">个人中心</a></li>
                          
                        <?php else: ?>
                         <li>
                        <a target="_blank" href="<?php echo url('login/login'); ?>">登录</a>
                    </li>

                    <li>
                        <a target="_blank" href="<?php echo url('login/register'); ?>">注册</a>
                    </li>
                    <li>
                            <a target="_blank" href="<?php echo URL_ROOT; ?>/admin.php">后台管理</a>
                        </li>
                        <?php endif; ?>


                </ul>
            </div>
        </div>
    </div>
</div>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" href="__STATIC__/index/css/mui.min.css">
    <link rel="stylesheet" href="__STATIC__/index/fonts/iconfont.css" />
    <link rel="stylesheet" href="__STATIC__/index/css/style.css" />
    <link rel="stylesheet" href="__STATIC__/index/css/login.css" />
</head>
<body>
<div class="login_bg"></div>
<div class="logo">
    <p class="logo-text"><?php echo config('seo_title'); ?></p>
</div>

<div class="login-view">
    <form action="<?php echo url('loginHandle'); ?>" method="post">
        <div class="login-tab">
            <div class="row-text">
                <i class="iconfont icon-yonghuming1 login-icon"></i>
                <input type="text" name="username" placeholder="请输入账号"/>
            </div>
            <div class="row-text">
                <i class="iconfont icon-mima4 login-icon"></i>
                <input type="password" name="password" placeholder="请输入密码" id="password"/>
            </div>
            <div class="row-text">
                <i class="iconfont icon-yanzhengma2 login-icon"></i>
                <input type="text" name="verify" placeholder="请输入验证码" maxlength="6"/>
                <img src="<?php echo captcha_src(); ?>" alt="captcha" onclick="this.src = '<?php echo captcha_src(); ?>'+Math.random()" class="login-auth"/>
            </div>
        </div>

        <div class="login-btn">
            <input type="submit" value="登  录">
        </div>
    </form>

    <a href="<?php echo url('index/login/register'); ?>" class="reglink">注册</a>
    <a href="<?php echo url('index/login/findw'); ?>" class="reglink">(找回密码)</a>
 <a href="<?php echo url('index/home'); ?>" class="reglink">首页</a>
</div>


</body>
<script type="text/javascript" src="../index/js/mui.min.js" ></script>
<script>
    mui('body').on('tap', 'a', function() {
        var id = this.getAttribute('href');
        var href = this.href;
        mui.openWindow({
            id: id,
            url: this.href,
            show: {
                autoShow: true
            }
        });
    });
</script>
</html>

<footer class="footer">
  <div class="container">
      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
