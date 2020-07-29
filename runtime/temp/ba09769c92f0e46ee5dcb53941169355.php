<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:69:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\members\tree.html";i:1595053000;s:63:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout.html";i:1585716400;s:67:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\top.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\header.html";i:1594175922;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<link rel="stylesheet" href="__STATIC__/index/thirdlib/ztree/css/zTreeStyle/zTreeStyle.css">
<script type="text/javascript" src="__STATIC__/index/js/jquery.min.js"></script>
<script type="text/javascript" src="__STATIC__/index/layui/layui.all.js"></script>
<script type="text/javascript" src="__STATIC__/index/thirdlib/thirdlib/ztree/js/jquery.ztree.all.min.js"></script>
<style type="text/css" media="screen">
    .ztree * {
        font-size: 16px;
    }
</style>
<body class="larryms-system">
<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
  <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
  <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <div class="layui-row larryms-panel">
    <div class="larryms-panel-heading layui-col-lg12 layui-col-md12 layui-col-sm12 layui-col-xs12">
      <span class="panel-tit">推荐关系</span>
    </div>
    <div class="larryms-panel-body layui-col-lg12 layui-col-md12 layui-col-sm12 layui-col-xs12">

        <blockquote class="layui-elem-quote quoteBox" id="articleBtn">

                <form action="<?php echo url(); ?>" method="get" class="layui-form layui-inline">
                    <div class="larryms-search-box">
                        <div class="layui-input-inline" style="width: 120px;">
                            <input type="text" name="root_id" class="layui-input searchVal layui-inline larry-input" placeholder="输入会员账号" autocomplete="off" value="<?php echo \think\Request::instance()->param('root_id'); ?>">
                        </div>
                        <button class="layui-btn larryms-search" id="searchBtn" data-type="reload" type="submit">搜索</button>
                    </div>
                </form>
                
            </blockquote>


      <div class="larryms-tools">
          </div>
            <div class="user-list layui-col-lg12 layui-col-md12 layui-col-sm12 layui-col-xs12">
                <ul id="ztree" class="ztree" style="overflow-x:scroll;"></ul>
            </div>
    </div>
  </div>

<script type="text/javascript">
var table;
layui.cache.page = 'system'; 
layui.use(['larry'], function() {
    var $ = layui.$;
    var zTreeObj;
    var setting = {
        callback: {
            onClick: get_members,
            onExpand: get_members
        },
        view: {
            nameIsHTML: true,
            showIcon: false,
        }
    };
    var zNodes = <?php echo raw($data); ?>;
    $(document).ready(function() {
        zTreeObj = $.fn.zTree.init($("#ztree"), setting, zNodes);
    });

    var map = { <?php echo $root['id']; ?> : true };

    function get_members(event, treeId, treeNode, clickFlag) {
        if (map[treeNode.id] || !treeNode.children) {
            return;
        }
        $.ajax({
            url: "<?php echo url('getChildrenMembers'); ?>",
            data: { "pid": treeNode.id },
            success: function(data) {
                if (data.code == 1) {
                    var jsondata = eval(data.msg);
                    if (jsondata == null || jsondata == "") {
                        //末节点的数据为空   所以不再添加节点  这里可以根据业务需求自己写
                    } else {
                        var treeObj = $.fn.zTree.getZTreeObj(treeId);
                        var parentZNode = treeObj.getNodeByParam("id", treeNode.id, null); //获取指定父节点
                        newNode = treeObj.addNodes(parentZNode, jsondata, false);
                        map[treeNode.id] = true;
                    }
                }else{
                    obalert(data);
                }

            }
        });
    };
});
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
