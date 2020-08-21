<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\ep\ep_shop.html";i:1597979259;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
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
<link href="__STATIC__/index/layui/css/layui.css" rel="stylesheet">
<a class="btn" href="<?php echo url('user/index'); ?>" style="margin-top: 8px;margin-left: 8px;"><i class="fa fa-history"></i> 返 回</a>
    <table  class="table table-bordered" style="margin-top: 55px;">
      <thead>
      <tr>
          <th></th>
          <th>买入</th>
          <th>卖出</th>
      </tr>
      </thead>
        <tbody>
                <tr>
                  <td></td>
                  <td>当前市场比例：1 : <?php echo $list2['ep_pro']; ?>
                      当前账户持有Ep：<?php echo $list1['bonus']; ?>  个
                  </td>
                  <td>当前市场比例：1 : <?php echo $list2['ep_pro']; ?>
                      当前账户持有Ep： <?php echo $list1['bonus']; ?> 个
                  </td>
                </tr>
                <tr>
                  <td>操作</td>
                  <form action="" method="post" name="form1">
                  <td><input type="number" name="amount" value="" placeholder="输入挂买数量" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
                      <button onclick="buy()">挂买</button>
                      <button onclick="withBuyS()">挂买列表</button>
                      <button onclick="buy_list()">交易列表</button>
                  </td>
                  </form>

                  <form action="" method="post" name="form2">
                  <td>
                      <button onclick="sell()">挂卖</button>
                      <button onclick="withSellS()">撤销挂卖</button>
                      <button onclick="sell_list()">交易列表</button>
                  </td>
                  </form>
                </tr>
       
        </tbody>
    </table>
    <table class="table table-bordered" style="width: 48%;float: left">
        <thead>
        <tr>
            <td>用户</td>
            <td>挂卖</td>
            <td>剩余成交</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <?php if(!(empty($list3) || (($list3 instanceof \think\Collection || $list3 instanceof \think\Paginator ) && $list3->isEmpty()))): ?>
        <tbody>
        <?php if(is_array($list3) || $list3 instanceof \think\Collection || $list3 instanceof \think\Paginator): $i = 0; $__LIST__ = $list3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>

        <tr>
            <form action="<?php echo url('buy_ali'); ?>"   method="post" >
            <input name="id" id="id" hidden value="<?php echo $vo3['id']; ?>">
            <td>
                <?php 
                $mem = db('member') -> getById( $vo3 -> member_id ) ;
                 ?>
                <?php echo $mem['username']; ?>
            </td>
            <td><?php echo $vo3['num_a']; ?></td>
            <td><?php echo $vo3['num']; ?></td>
            <td><?php if($vo3['status'] == 1): ?>挂卖中
                <?php elseif($vo3['status'] ==2): ?>订单完成
                <?php elseif($vo3['status'] ==3): ?>订单关闭
                <?php endif; ?>
            </td>
            <td>
                <input type="number" name="buy_alist" class="buy_alis" value="" onchange="buy_ali()">
                <button type="submit">部分交易</button>
<!--                <button lay-submit lay-filter="buy_ali">部分交易</button>-->
                <input hidden name="buy_all" id="buy_all" value="<?php echo $vo3['num']; ?>">
                <input hidden name="ep_pro" id="ep_pro" value="<?php echo $list2['ep_pro']; ?>">
            </form>
                <a href="<?php echo url('ep/buy_all',['id'=>$vo3['id'],'buy_all'=>$vo3['num'],'ep_pro'=>$list2['ep_pro']]); ?>"><button>全部交易</button></a>
<!--                <button lay-submit lay-filter="buy_all">全部交易</button>-->
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
            <td>用户</td>
            <td>挂买</td>
            <td>剩余成交</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <?php if(!(empty($list4) || (($list4 instanceof \think\Collection || $list4 instanceof \think\Paginator ) && $list4->isEmpty()))): ?>
        <tbody>
        <?php if(is_array($list4) || $list4 instanceof \think\Collection || $list4 instanceof \think\Paginator): $i = 0; $__LIST__ = $list4;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo4): $mod = ($i % 2 );++$i;?>

            <tr>
                <form action="<?php echo url('Ep/sell_ali'); ?>" method="post" name="form4">
                <input name="id" hidden value="<?php echo $vo4['id']; ?>">
                <td><?php 
                    $mem = db('member') -> getById( $vo4 -> member_id ) ;
                     ?>
                    <?php echo $mem['username']; ?>
                </td>
                <td><?php echo $vo4['num_a']; ?></td>
                <td><?php echo $vo4['num']; ?></td>
                <td><?php if($vo4['status'] == 1): ?>挂买中
                    <?php elseif($vo4['status'] ==2): ?>订单完成
                    <?php elseif($vo4['status'] ==3): ?>订单关闭
                    <?php endif; ?>
                </td>
                <td>
                    <input type="number" name="sell_alist" value="" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
                    <button type="submit">部分交易</button>
                    <input hidden name="sell_all" value="<?php echo $vo4['num']; ?>">
                    <input hidden name="ep_pro" value="<?php echo $list2['ep_pro']; ?>">
                </form>
<!--                    <button onclick="sell_all_1()">全部交易</button>-->
                    <a href="<?php echo url('ep/sell_all',['id'=>$vo4['id'],'sell_all'=>$vo4['num'],'ep_pro'=>$list2['ep_pro']]); ?>"><button>全部交易</button></a>
                </td>
            </tr>

        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="8" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
        <?php endif; ?>
    </table>
 <script src="/static/index/sentsin-layui-master/layui/dist/layui.js"></script>
    <script>
        //挂买
        function buy(){
            document.form1.action="<?php echo url('Ep/sys_buyEp'); ?>";
            document.form1.submit();
        }
        //自己的购买列表
        function buy_list() {
            document.form1.action = "<?php echo url('Ep/buy_list'); ?>";
            document.form1.submit();
        }
        //撤销挂买
        function withBuyS() {
            document.form1.action = "<?php echo url('Ep/withBuy_list'); ?>";
            document.form1.submit();
        }
        //挂卖
        function sell() {
            document.form2.action = "<?php echo url('Ep/sys_sellEp'); ?>";
            document.form2.submit();
        }
        //撤销挂卖
        function withSellS() {
            document.form2.action = "<?php echo url('Ep/withSellEp'); ?>";
            document.form2.submit();
        }
        //自己的出售列表
        function sell_list() {
            document.form2.action = "<?php echo url('Ep/sell_list'); ?>";
            document.form2.submit();
        }
        // //有人挂卖时，部分交易
        // function buy_ali() {
        //     document.form3.action = "<?php echo url('Ep/buy_ali'); ?>";
        //     document.form3.submit();
        // }
        // //有人挂卖时，全部交易
        // function buy_all_1() {
        //     document.form3.action = "<?php echo url('Ep/buy_all'); ?>";
        //     document.form3.submit();
        // }
        layui.use(['form','layer','jquery'], function(){
            var form = layui.form;
            var $ = layui.jquery;
            var layer = layui.layer;
            form.on('submit(buy_ali)', function(data){
                    alert($('.buy_alis').val())
                    //发起ajax请求
                    $.ajax({
                        url:"<?php echo url('buy_ali'); ?>",//注意：这里实际情况应该是根据点击的不同a标签加载不同页面
                        data:{
                            id:$('#id').val(),
                            buy_alist:$('.buy_alis').val(),
                            buy_all:$('#buy_all').val(),
                            ep_pro:$('#ep_pro').val(),
                        },
                        dataType:'json',
                        type:'post',
                        success: function(data){
                            //将请求回来的内容添加到下面的内容div
                            if(data==1){
                                layer.msg('订单匹配成功')
                                window.location.href = 'buy_list.html';
                            }else if(data == 3){
                                layer.msg('不能与自己交易')

                            }else if(data == 2){
                                layer.msg('当前还有未付款订单')
                            }else if(data == 4){
                                layer.msg('请输入有效数字')

                            }else if(data == 5){
                                layer.msg('请输入不大于剩余挂卖的值')
                            }
                        }
                    });
                return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
            });
            form.on('submit(buy_all)', function(data){
                //发起ajax请求
                $.ajax({
                    url:"<?php echo url('buy_all'); ?>",//注意：这里实际情况应该是根据点击的不同a标签加载不同页面
                    data:{
                        id:$('#id').val(),
                        buy_alist:$('#buy_alis').val(),
                        buy_all:$('#buy_all').val(),
                        ep_pro:$('#ep_pro').val(),
                    },
                    dataType:'json',
                    type:'post',
                    success: function(data){

                        //将请求回来的内容添加到下面的内容div
                        if(data==1){
                            layer.msg('订单匹配成功')
                            window.location.href = 'buy_list.html';
                        }else if(data == 3){
                            layer.msg('不能与自己交易')

                        }else if(data == 2){
                            layer.msg('当前还有未付款订单')
                        }
                    }
                });

            });
        });
        //有人挂买时，部分交易
        function sell_ali() {
            document.form4.action = "<?php echo url('Ep/sell_ali'); ?>";
            document.form4.submit();
        }
        //有人挂买时，全部交易
        function sell_all_1() {
            document.form4.action = "<?php echo url('Ep/sell_all'); ?>";
            document.form4.submit();
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
