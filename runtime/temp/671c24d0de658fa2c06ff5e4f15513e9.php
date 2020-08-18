<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:72:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\login\register.html";i:1597129346;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
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
    <h1 class="mui-title header-h1">注册</h1>
</header>
<form id="form1" action="<?php echo url('login/register'); ?>" method="post">
    <div class="content-view">
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>推荐人(必填)</label></div>
            <div class="mui-col-xs-8"><input type="text" name="leader_username" class="code_input" value="<?php echo (isset($leader2) && ($leader2 !== '')?$leader2:''); ?>" placeholder="请输入推荐人姓名"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>接点人(必填)</label></div>
            <div class="mui-col-xs-8"><input type="text" name="contact_name" class="code_input" value="<?php echo (isset($leader2) && ($leader2 !== '')?$leader2:''); ?>" placeholder="请输入接点人姓名"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>报单中心(必填)</label></div>
            <div class="mui-col-xs-8"><input type="text" name="center_name" class="code_input" value="<?php echo (isset($leader2) && ($leader2 !== '')?$leader2:''); ?>" placeholder="请输入所属报单中心"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>选择对应等级</label></div>
            <div class="mui-col-xs-8">
                <select name="member_rank">
                    <option value="1">一星注册</option>
                    <option value="2">二星注册</option>
                    <option value="3">三星注册</option>
                    <option value="4">四星注册</option>
                    <option value="5">五星注册</option>
                    <option value="6">六星注册</option>
                </select>
            </div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>手机号</label></div>
            <div class="mui-col-xs-8"><input type="text" name="mobile" class="code_input" value="" placeholder="请输入手机号" maxlength="11" id="mobile" oninput = "value=value.replace(/[^\d]/g,'')"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>银行名称</label></div>
            <div class="mui-col-xs-8"><input type="text" name="bankname" class="code_input" value="" placeholder="请输入银行名称" maxlength="30" id="bankname"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>银行卡号</label></div>
            <div class="mui-col-xs-8"><input type="text" name="bankcard" class="code_input" value="" placeholder="请输入银行卡号" maxlength="30" id="bankcard" oninput = "value=value.replace(/[^\d]/g,'')"></div>
        </div>

        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>用户名</label></div>
            <div class="mui-col-xs-8"><input type="text" name="realname" class="code_input" placeholder="请输入您的真实姓名"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>登录密码</label></div>
            <div class="mui-col-xs-8"><input type="password" name="password" class="code_input" placeholder="请输入登录密码"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>确认密码</label></div>
            <div class="mui-col-xs-8"><input type="password" name="password_confirm" class="code_input" placeholder="请再次输入密码"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保问题</label></div>
            <div class="mui-col-xs-8"><input type="text" name="mibaowenti" class="code_input" placeholder="请输入您的密保问题"></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保答案</label></div>
            <div class="mui-col-xs-8"><input type="text" name="mibaodaan" class="code_input" placeholder="请输入您的密保答案"></div>
        </div>
        <div class="mui-row code_cell">
            <label><input name="treeplace" type="radio" value="0" />左区注册 </label> 
        <label><input name="treeplace" type="radio" value="1" />右区注册 </label> 
        </div>
        
        <button type="submit" class="code_btn" style="line-height: 14px" >立即注册</button>
    </div>
</form>

</body>

<script src="__STATIC__/index/js/mui.min.js"></script>
<script src="__STATIC__/index/js/jquery.min.js"></script>

</html>
<footer class="footer">
  <div class="container">
<!--      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>-->
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
