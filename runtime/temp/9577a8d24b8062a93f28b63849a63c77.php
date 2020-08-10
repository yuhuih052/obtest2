<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\ep_deal\record.html";i:1596791997;}*/ ?>

<div class="box">

    <div>
        <form action="searcData" method="post">
            <input type="date" name="date" value="">
            <button type="submit">查询</button>
        </form>
    </div>

    <div class="box-body table-responsive">

        <table  class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>买家</th>
                <th>卖家</th>
                <th>数量</th>
                <th>汇率</th>
                <th>金额</th>
                <th>日期</th>
            </tr>
            </thead>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td>
                    <?php 
                    $mem = db('member') -> getById( $vo -> buyer_id ) ;
                     ?>
                    <?php echo $mem['username']; ?>
                </td>
                <td><?php 
                    $mem = db('member') -> getById( $vo -> seller_id ) ;
                     ?>
                    <?php echo $mem['username']; ?>
                </td>
                <td>$<?php echo $vo['ep_amount']; ?></td>
                <td>1:<?php echo $vo['ep_pro']; ?></td>
                <td>￥<?php echo $vo['ep_money']; ?></td>
                <td><?php echo $vo['create_time']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <?php endif; ?>
        </table>
    </div>

    <div class="box-footer clearfix text-center">
        <?php echo $list->render(); ?>
    </div>

    <a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
</div>