<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:75:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\login\findpassword.html";i:1593958249;s:63:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout.html";i:1585716400;s:67:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\top.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\header.html";i:1594175922;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
            <a class="brand" href="<?php echo url('index/index'); ?>">OneBase</a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                <ul class="nav">
                        <li>
                            <a href="<?php echo url('index/index'); ?>">首页</a>
                        </li>
                        <li>
                            <a href="<?php echo url('index/index'); ?>">新闻公告</a>
                        </li>

                        <?php if(\think\Request::instance()->session('user_id') != null): ?>
                          <li><a href="<?php echo url('user/index',['id'=> \think\Request::instance()->session('user_id')]); ?>">个人中心</a></li>
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
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>注册</title>
    <link rel="stylesheet" href="__STATIC__/index/css/mui.min.css"/>
    <link rel="stylesheet" href="__STATIC__/index/fonts/iconfont.css" />
    <link rel="stylesheet" href="__STATIC__/index/css/style.css" />
</head>
<body>
<header class="mui-bar mui-bar-nav headernav-bg">
    <a href="javascript:window.history.go(-1);" class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left header-a1"></a>
    <h1 class="mui-title header-h1">找回密码</h1>
</header>
<form id="form1" action="<?php echo url('/index/login/changePassword'); ?>" method="post">
    <div class="content-view">
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>用户名</label></div>
            <div class="mui-col-xs-8"><input type="text" name="username" class="code_input" value="<?php echo $data['0']['username']; ?>" readonly  unselectable="on"></div>
        </div>

        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>会员编号</label></div>
            <div class="mui-col-xs-8"><input type="text" name="member_number" class="code_input" readonly  unselectable="on" value="<?php echo $data['0']['member_number']; ?>"></div>
        </div>

        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>登录密码</label></div>
            <div class="mui-col-xs-8"><input type="password" name="password" class="code_input" value="" placeholder=""></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保问题</label></div>
            <div class="mui-col-xs-8"><input type="text" name="mibaowenti" class="code_input" readonly  unselectable="on" value="<?php echo $data['0']['mibaowenti']; ?>" placeholder=""></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保答案</label></div>
            <div class="mui-col-xs-8"><input type="text" name="mibaodaan" class="code_input" value="" placeholder="请输入答案"></div>
        </div>
        <div class="row-text">
            <i class="iconfont icon-yanzhengma2 login-icon"></i>
            <input type="text" name="verify" placeholder="请输入验证码" maxlength="6"/>
            <img src="<?php echo captcha_src(); ?>" alt="captcha" onclick="this.src = '<?php echo captcha_src(); ?>'+Math.random()" class="login-auth" style="height: 130px
;width: 500px"/>
        </div>
        <button type="submit" class="code_btn" style="line-height: 14px" >立即修改</button>
    </div>
</form>

</body>


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
