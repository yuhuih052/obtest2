<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\login\login.html";i:1597030787;}*/ ?>
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
                <img src="<?php echo captcha_src(); ?>" alt="captcha" onclick="this.src = '<?php echo captcha_src(); ?>'" class="login-auth"/>
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
<script type="text/javascript" src="__STATIC__/index/js/mui.min.js" ></script>
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
