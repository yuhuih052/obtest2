<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:74:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\user\two_pathway.html";i:1595991336;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<style type="text/css">
    td{
        text-align: center;
    }
    .two-pathway{padding-bottom: 50px;}
    .td{
        border:1px solid #ccc;
        height: 80px;
        width: 80px;
        display: inline-block;
        padding: 5px;
        margin: 5px;
        margin-top: 30px;
    }
    .td{position: relative;}
    .td:before{position: absolute; content: ''; height: 16px; width: 1px; background-color: #333; left: 50%; top: -16px}
    .td:after{position: absolute; content: ''; height: 21px; width: 1px; background-color: #333; left: 50%; bottom: -21px}
    table tr:first-child .td:before{display: none}
    table tr:last-child .td:after{display: none}
    table tr td{position: relative;}
    table tr td:after{position: absolute; content: ''; height:1px; width: 50%; background-color: #333; left: 50%; bottom: -16px; left:25%;}
    table tr:last-child td:after{display: none}

    .no-data{
        text-align: center;
        padding: 20px;
        font-size: 18px;
    }

    .btn-yellow:visited {
        color: #fff; 
    }

    .btn-yellow{
        line-height: 30px !important;
    }

    .two-pathway a{
        color:#5db6e5;
    }

</style>
<div class="head">
   <a href="javascript:history.back();" class="back_btn"></a>
        我的团队
    </div>

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
                <button onclick="left()" name="place" value="0">左区注册</button>
                <button onclick="right()" name="place" value="1">右区注册</button>

    </form>
     
 
  <!-- E=头部 -->
<div class="height-top"></div>

<div class="container">
    <div style="margin-bottom: 3rem;" >
      <div style="width: 100%; overflow: auto;">
            <div class="two-pathway" style="overflow-x:scroll;">
                    <?php echo $html; ?>
                </div>
        </div>
    </div>
</div>
<div style="height: 30px"></div>
<script type="text/javascript" src="__STATIC__/index/js/jquery.min.js"></script>
<script type="text/javascript">

    function left(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="<?php echo url('placeTreesize'); ?>";
        document.form.submit();
    }
    function right() {
        document.form.action = "<?php echo url('placeTreesize'); ?>";
        document.form.submit();
    }
    function find(){
        document.form.action="<?php echo url('twoPathwayfind'); ?>";
        document.form.submit();
    }
    function ding(){
        document.form.action="<?php echo url('twoPathway'); ?>";
        document.form.submit();
    }


      $(document).ready(function(){
        var uaername = $('#username').val();
        var level = $('#level').val();
        
          $("#left").click(function(){
            var place = 0;
            //alert(place);
            $.post("placeTreesize",{
                username:username.value,
                level:level,
                place:place,
            },function(){
                location.reload();
            }); 
        });
        $("#right").click(function(){
            var place = 1;
            $.post("placeTreesize",{
                username:username.value,
                level:level,
                place:place,
            });      
    });
});
</script>
<a href="<?php echo url('user/index',['id'=> \think\Request::instance()->session('user_id2')]); ?>">返回个人列表</a>
<footer class="footer">
  <div class="container">
      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
