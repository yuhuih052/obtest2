<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:71:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\user\team_tree.html";i:1594796318;s:63:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout.html";i:1585716400;s:67:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\top.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\header.html";i:1594175922;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<script type="text/javascript" src="__STATIC__/module/index/js/ztree/js/jquery.ztree.all.min.js"></script>
<style type="text/css" media="screen">
    .ztree * {
        font-size: 16px;
    }
    .button{box-shadow: none;}
    ul{border-bottom: 0;}
</style>
<!-- 背景图 -->
<div class="body"></div>
<div class="head">
    <a href="javascript:history.back();" class="back_btn"><i class="iconfont icon-zuo-"></i></a>
    第<?php echo \think\Request::instance()->param('level'); ?>层会员
</div>
<div class="container">
    <div class="fs-tab">
       <table class="table-fs">
            <tr>
                <th>会员编号</th>
                <th>手机</th>
                <th>等级</th>
            </tr>
            <php>$list = $member->xiaji(request()->param('level'));</php>
            <volist name="$list" id="vo">
                <tr>
                   <td><span><?php echo $vo['username']; ?></span></td>
                   <td><span><?php echo $vo['mobile']; ?></span></td>
                   <td><?php echo raw($vo['level_html']); ?></td>
                </tr>
            </volist>
       </table>
    </div>
</div>
<div align="center"><?php echo raw($list->render()); ?></div>


<div style="height: 30px"></div>

<script LANGUAGE="JavaScript">
var zTreeObj;
var setting = {
    callback: {
        onClick: get_members,
        onExpand: get_members
    },
    view: {
        nameIsHTML: true,
        showIcon: false
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
