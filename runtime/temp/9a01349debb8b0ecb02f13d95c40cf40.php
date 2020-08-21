<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:69:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\ep\buy_list.html";i:1597831825;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<table class="table table-bordered" style="width: 48%;margin-top: 55px;">
    <thead>
    <tr>
        <td>交易量</td>
        <td>金额</td>
        <td>银行卡号</td>
        <td>手机号码</td>
        <td>状态</td>
        <td>时间</td>
        <td>操作</td>
    </tr>
    </thead>
    <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
    <tbody>
    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    <tr>
        <td><?php echo $vo['ep_amount']; ?></td>
        <td><?php echo $vo['ep_money']; ?></td>
        <td>
            <?php 
            $mem = db('member') -> getById( $vo -> seller_id ) ;
             ?>
            <?php echo $mem['bankcard']; ?>
        </td>
        <td>
            <?php 
            $mem = db('member') -> getById( $vo -> seller_id ) ;
             ?>
            <?php echo $mem['mobile']; ?>
        </td>
        <td><?php if($vo['flag'] == 1): ?>等待打款
            <?php elseif($vo['flag'] ==2): ?>等待确认
            <?php elseif($vo['flag'] ==3): ?>完成交易
            <?php elseif($vo['flag'] ==4): ?>交易关闭
            <?php elseif($vo['flag'] ==-4): ?>拒绝交易
            <?php elseif($vo['flag'] ==-1): ?>仲裁
            <?php elseif($vo['flag'] ==5): ?>仲裁中
            <?php endif; ?>
        </td>
        <td><?php echo $vo['create_time']; ?></td>
        <td>
            <?php if($vo['flag'] == 1): ?>
<!--            上传打款凭证-->
            <form action="<?php echo url('Ep/upload'); ?>" enctype="multipart/form-data" method="post">
                <span>请选择付款截图</span><br>
                <input type="file" name="image" /> <br>
                <input hidden name="id" value="<?php echo $vo['id']; ?>">
                <input type="submit" value="上传"></input><br>

            </form>
            <form action="<?php echo url('ep/cancel_deal'); ?>" method="post">
               <input type="submit" value="取消订单">
                <input hidden name="id" value="<?php echo $vo['id']; ?>">
            </form>
            <form name="form1" action="" method="post">
            <?php elseif($vo['flag'] ==2): ?>
                <img src="/<?php echo $vo['screenshot']; ?>" alt="付款截图" width="180px" height="90"> <br>
                等待卖家确认收款
            </form>
            <?php elseif($vo['flag'] ==3): ?>完成交易
            <?php elseif($vo['flag'] ==4): ?>交易关闭
            <?php elseif($vo['flag'] ==-4): ?>拒绝交易
            <?php elseif($vo['flag'] ==-1): ?>仲裁
            <?php elseif($vo['flag'] ==5): ?>
                <img src="/<?php echo $vo['screenshot']; ?>" alt="付款截图" width="180px" height="90"> <br>
                <form action="<?php echo url('Ep/re_upload'); ?>" enctype="multipart/form-data" method="post">
                    <input type="file" name="image" /> <br>
                    <input hidden name="id" value="<?php echo $vo['id']; ?>">
                    <input type="submit" value="重新上传付款截图"></input><br>
                </form>
            <?php endif; ?>

        </td>

    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    <?php else: ?>
    <tbody><tr class="odd"><td colspan="8" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
    <?php endif; ?>
</table>
<a class="btn" href="<?php echo url('ep/ep_shop'); ?>"><i class="fa fa-history"></i> 返 回</a>
</body>
<script>

    $(function () {
        //禁用“确认重新提交表单”
        window.history.replaceState(null, null, window.location.href);
    })
</script>
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
