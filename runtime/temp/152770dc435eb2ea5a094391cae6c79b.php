<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:72:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\user\zhuanhuan.html";i:1597457554;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
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
    <link href="__STATIC__/index/layui/css/layui.css" rel="stylesheet">
    
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
<form style="margin-top: 55px;" action="<?php echo url('user/zhuanhuan1'); ?>" method="post" class="form_single">
  <?php echo token(); ?>
    <div class="box" style="margin: 40px;">
      <div class="box-body">
              <label>输入额度：</label>
              
              <input class="form-control" name="zhuanhuan" required="required" placeholder="请输入正整数" type="text" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')">
      </div>
      <select name="zhuanhuan_type">
        <option value="1">现金币转报单币</option>
        <option value="2">现金币转保管金</option>
        <option value="3">保管金转报单币</option>
      </select>
      <div class="box-footer">
        <button  type="submit" class="btn ladda-button ajax-post" data-style="slide-up" target-form="form_single">
    <span class="ladda-label"><i class="fa fa-send"></i> 转换</span>
</button>
<a class="btn" href="<?php echo url('user/index',['id'=> \think\Request::instance()->session('user_id2')]); ?>"><i class="fa fa-history"></i> 返 回</a>
        
      </div>
    </div>
</form>
<script type="text/javascript">
  
</script>
<footer class="footer">
  <div class="container">
<!--      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>-->
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
