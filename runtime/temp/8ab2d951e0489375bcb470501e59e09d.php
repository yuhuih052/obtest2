<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:70:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\cfi\cfi_shop.html";i:1597633970;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
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
  <table style="margin-top: 50px;" class="table table-bordered">
      <thead>
      <tr>
          <th>当前cfi价格:<?php echo $list['cfi_price']; ?></th>
          <th>买入</th>  
          <th>卖出</th>
      </tr>
      </thead>
        <tbody>
                <tr>
                  <td></td>
                  <td>当前市场单价：$<?php echo $list['cfi_price']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!$list2 == Null): ?>
                      当前账户正在挂买:<?php echo $list2['dianzibi']; ?> 币
                    <?php endif; ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      当前账户持有CFI： <?php echo $list3['CFI']; ?> 个
                  </td>
                  <td>当前市场单价：$<?php echo $list['cfi_price']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!$list2 == Null): ?>
                      当前账户正在挂卖：<?php echo $list2['sell']; ?>个
                    <?php endif; ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      当前账户持有CFI： <?php echo $list3['CFI']; ?> 个
                  </td>
                </tr>
                <tr>
                  <td>操作</td>
                  <form action="" method="post" name="form1">
                  <td><input type="number" name="cfi_amount" value="" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"
                             onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
                    <input hidden="" name="cfi_price" value="<?php echo $list['cfi_price']; ?>">
                      <input hidden name="dianzibi" value="<?php echo $list2['dianzibi']; ?>">
                      <button onclick="buy()">买入</button>
                      <button onclick="withBuyS()">撤销挂买</button>
                  </td>
                  </form>
                  <form action="" method="post" name="form2">
                  <td><input type="number" name="cfi_amount" value="" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"
                             onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
                    <input hidden="" name="cfi_price" value="<?php echo $list['cfi_price']; ?>">
                      <input hidden name="cfi" value="<?php echo $list2['sell']; ?>">
                      <button onclick="sell()">卖出</button>
                      <button onclick="withSellS()">撤销挂卖</button>
                  </td>
                  </form>
                </tr>
       
        </tbody>
    </table>
<table class="table table-bordered" style="width: 48%;float: left">
    <thead>
    <tr>
        <td>挂买</td>
        <td>剩余成交</td>
        <td>状态</td>
    </tr>
    </thead>
    <?php if(!(empty($list4) || (($list4 instanceof \think\Collection || $list4 instanceof \think\Paginator ) && $list4->isEmpty()))): ?>
    <tbody>
    <?php if(is_array($list4) || $list4 instanceof \think\Collection || $list4 instanceof \think\Paginator): $i = 0; $__LIST__ = $list4;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo4): $mod = ($i % 2 );++$i;?>
    <tr>

        <td><?php echo $vo4['buy']; ?></td>
        <td><?php echo $vo4['dianzibi']; ?></td>
        <td><?php if($vo4['statuss'] == 1): ?>挂买中
            <?php elseif($vo4['statuss'] ==2): ?>订单完成
            <?php elseif($vo4['statuss'] ==3): ?>订单关闭
            <?php endif; ?>
        </td>

    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    <?php else: ?>
    <tbody><tr class="odd"><td colspan="8" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
    <?php endif; ?>
</table>
    <table class="table table-bordered" style="width: 48%;float: right">
        <thead>
        <tr>
            <td>挂卖</td>
            <td>剩余成交</td>
            <td>状态</td>
        </tr>
        </thead>
        <?php if(!(empty($list5) || (($list5 instanceof \think\Collection || $list5 instanceof \think\Paginator ) && $list5->isEmpty()))): ?>
        <tbody>
        <?php if(is_array($list5) || $list5 instanceof \think\Collection || $list5 instanceof \think\Paginator): $i = 0; $__LIST__ = $list5;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo5): $mod = ($i % 2 );++$i;?>
        <tr>

            <td><?php echo $vo5['sell_a']; ?></td>
            <td><?php echo $vo5['sell']; ?></td>
            <td><?php if($vo5['statuss'] == 1): ?>挂卖中
                <?php elseif($vo5['statuss'] ==2): ?>订单完成
                <?php elseif($vo5['statuss'] ==3): ?>订单关闭
                <?php endif; ?>
            </td>

        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="8" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
        <?php endif; ?>
    </table>
    <script>
        function buy(){
//        document.("表单的name值").action
//        document.("表单的name值").submit;
          
            document.form1.action="<?php echo url('Cfi/sys_buy'); ?>";

            document.form1.submit();
        }
        function sell() {
            document.form2.action = "<?php echo url('Cfi/sys_sell'); ?>";
            document.form2.submit();
        }
        function cont(){
            document.form1.action="<?php echo url('Cfi/sys_buy'); ?>";
            document.form1.submit();
        }
        function withBuyS() {
            document.form1.action = "<?php echo url('Cfi/withBuyS'); ?>";
            document.form1.submit();
        }
        function withSellS() {
            document.form2.action = "<?php echo url('Cfi/withSellS'); ?>";
            document.form2.submit();
        }
        $(function () {
            //禁用“确认重新提交表单”
            window.history.replaceState(null, null, window.location.href);
        })
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
