<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:68:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\user\system.html";i:1594978632;s:63:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout.html";i:1585716400;s:67:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\top.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\header.html";i:1594175922;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
    <title>雙軌關係圖</title>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/mui.min.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/own.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/cart.css" />

</head>
<body>
<style>
    .mui-control-content {
        background-color: white;
        min-height: 215px;
    }
    .mui-control-content .mui-loading {
        margin-top: 50px;
    }
    .sum-money{
        float: left;
        padding-left: 10px;
    }
    .mui-bar-nav~.mui-content{ padding-top:0; }
    .mui-bar .mui-icon {
        font-size: 24px;
        position: relative;
        z-index: 20;
        padding-top: 10px;
        padding-bottom: 10px;

        text-decoration: none;

    }
    .mui-bar .mui-title{ line-height: 50px; }
    .mui-bar-tab{ bottom: 0px; }

</style>

<header class="mui-bar mui-bar-nav own-main-background-color" style="height: 50px">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.back()"></a>
    <h1 class="mui-title">雙軌關係圖</h1>
</header>
<div style="height: 50px;"></div>
<form action="" name="form" method="post">
        用户：<input type="text" id="username" name="username" placeholder="输入用户名" value="">
                <select id="level" name="level" selected="4">
                    <option value="1" name="1">第一层</option>
                    <option value="2" name="2">第二层</option>
                    <option value="3" name="3">第三层</option>
                    <option value="4" name="4" selected>第四层</option>
                </select>
                <button onclick="find()">查询</button>
                <button onclick="ding()">顶层</button>
               <!-- <a id="left" name="left">左区注册</a>
                <a id="right" name="right">右区注册</a> -->
                <a href="<?php echo url('system',['id'=>$id]); ?>"><button>上一层</button></a>
                <button onclick="left()" name="place" value="0">左区注册</button>
                <button onclick="right()" name="place" value="1">右区注册</button>

    </form>
<div class="container">
    <?php echo $html; ?>
</div>
<div style="height: 80px;"></div>
<script type="text/javascript" charset="UTF-8">
    function test2(){
        layer.open({
            content: '确定操作？',
            btn: ['确认','取消'],
            yes: function(index, layero) {
                layer.close(index)
                var form = new FormData(document.getElementById("tf"));
                $.ajax({
                    url:"<?php echo url('shop/pay'); ?>",
                    type:"post",
                    data:form,
                    processData:false,
                    contentType:false,
                    dataType: "json",//预期服务器返回的类型
                    success:function(res){
                        mui.alert(res.msg, "提示", "關閉");
                        if (res.code == 1){
                            setTimeout(function () {
                                location.reload()
                            },1000)
                        }
                    },
                    error:function(e){

                    }
                });
            },
            btn2: function(index, layero) {
            }
        });

    }

    function left(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="<?php echo url('left'); ?>";
        document.form.submit();
    }
    function right() {
        document.form.action = "<?php echo url('right'); ?>";
        document.form.submit();
    }
    function find(){
        document.form.action="<?php echo url('system'); ?>";
        document.form.submit();
    }
    function ding() {
        document.form.action = "<?php echo url('system'); ?>";
        document.form.submit();
    }
    $(function () {
            //禁用“确认重新提交表单”
            window.history.replaceState(null, null, window.location.href);
        })

</script>
<footer class="footer">
  <div class="container">
      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
