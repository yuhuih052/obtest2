<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:74:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\user\my_team_tree.html";i:1595055917;s:63:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout.html";i:1585716400;s:67:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\top.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\header.html";i:1594175922;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<link rel="stylesheet" href="__STATIC__/module/index/js/ztree/css/zTreeStyle/zTreeStyle.css">
<style type="text/css">
    .search .btn{ vertical-align: top; height: 40px; margin-right: 20px; width: 80px; background-color: #c39535; text-align: center; color: #fff; line-height: 40px; padding: 0;  }
    .search .btn:hover{  color: #fff; box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.4)}
    .ztree *{ font-family: '微软雅黑'; font-size: 14px; }
    .ztree li{ line-height: 16px; }
    .ztree-rank{ color: #f8ba27; }
    .ztree li span.button.ico_open{  background-size: cover !important;}
    .ztree li span.button.ico_close{ background-size: cover !important;}
    .ztree li span.button.ico_docu{ background-size: cover !important;}
    .dlzhitui{width: calc(100% - 500px); float: left;}
    .forbid span{ color: #999 !important; }


    @media(max-width: 768px){
        .contentbox{padding: 0}
        .dlzhitui{ width: 100%; float: none; }
        .finance .search{ float: none !important; }
        .finance{padding: 0;}
        .finance .search input[type="text"]{ width: 70%; }
        .finance .search input[type="button"]{width: 30%;}
    }
</style>

<script type="text/javascript" src="__STATIC__/module/index/js/ztree/js/jquery.ztree.all.min.js"></script>
<header class="mui-bar mui-bar-nav own-main-background-color" style="height: 50px">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.back()"></a>
    <h1 class="mui-title">雙軌關係圖</h1>
</header>
<div class="contentbox">
    <div class="ibox-content finance" style="border: none; border-bottom:1px dashed #ddd;">
        <div style=" padding-bottom: 0px; margin-top: 60px">
            <dl class="dlzhitui">
                <dd>直推人數：<?php echo $root['0']['zhitui_all']; ?>人</dd>
                <dd>團隊人數：<?php echo $root['0']['team_all']; ?>人</dd>
            </dl>
            <div class="pull-right search" style="margin-top: 20px;">
                <if condition="$root.id neq $member.id">
                    <a href="#" class="btn">返回頂級</a>
                </if>

                <form method="get" action="<?php echo url('teamTree'); ?>" style="display: inline-block; width: 100%;">
                    <input type="text" value="<?php echo \think\Request::instance()->param('root_id'); ?>" placeholder="會員賬號" name="root_id">
                    <input type="button" value="查詢" name="">
                </form>
            </div><?php echo $data; ?>
            <div class="clearfix"></div>
        </div>
        <div style="width: 100%; overflow: auto;">
        <ul id="ztree" class="ztree"></ul>
        </div>
    </div>
</div>

<script LANGUAGE="JavaScript">
    var zTreeObj;
    var setting = {
        callback: {
            onClick: get_members,
            onExpand: get_members
        },
        view: {
            nameIsHTML: true
        }
    };
    var zNodes = <?php echo $data; ?>;
    $(document).ready(function() {
        zTreeObj = $.fn.zTree.init($("#ztree"), setting, zNodes);
    });
    //至今为止我们完全不知道为什么不能用map作为变量，我们也不敢问
    var flag = { <?php echo $root['0']['id']; ?> : true };
    function get_members(event, treeId, treeNode, clickFlag) {
        if (flag[treeNode.id] || !treeNode.children) {
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
                        flag[treeNode.id] = true;
                    }
                }else{
                    obalert(data);
                }

            }
        });
    };
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
