<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:74:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\user\member_edit.html";i:1595917413;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:79:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\edit_btn_group.html";i:1594017906;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<form action="<?php echo url('user/memberEdit'); ?>" method="post" class="form_single" style="margin: 30px;">
            <div class="form-group">
              <label>用户名</label>
              <input class="form-control" name="username" readonly value="<?php echo $info['username']; ?>" type="text">
            </div>
 
          <div class="col-md-6">
            <div class="form-group">
              <label>设置激活状态</label>
                <?php if($info['status'] == 0): ?>
                <label><input name="status" type="radio" value="0" checked />未激活 </label>
                <label><input name="status" type="radio" value="1" />激活 </label>
                <?php else: ?>
                <label><input name="status" type="radio" value="1" checked/>已激活 </label>
                <?php endif; ?>
            </div>
          </div>

      <div class="box-footer">
        
        <input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:'0'); ?>"/>
          
        <button  type="submit" class="btn ladda-button ajax-post" data-style="slide-up" target-form="form_single">
    <span class="ladda-label"><i class="fa fa-send"></i> 确 定</span>
</button>

<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
        
      </div>
    
</form>
<a href="<?php echo url('user/index',['id' => \think\Request::instance()->session('user_id2')]); ?>">个人列表</a>
<footer class="footer">
  <div class="container">
      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
