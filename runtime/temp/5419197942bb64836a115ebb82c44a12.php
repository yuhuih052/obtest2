<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\ep_deal\overtime.html";i:1596766764;}*/ ?>

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
                <th>买家Id</th>
                <th>卖家Id</th>
                <th>数量</th>
                <th>汇率</th>
                <th>金额</th>
                <th>订单创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $vo['buyer_id']; ?></td>
                <td><?php echo $vo['seller_id']; ?></td>
                <td>$<?php echo $vo['ep_amount']; ?></td>
                <td>1:<?php echo $vo['ep_pro']; ?></td>
                <td>￥<?php echo $vo['ep_money']; ?></td>
                <td><?php echo $vo['create_time']; ?></td>
                <td>
                    <form action="<?php echo url('EpDeal/cancel_overtime'); ?>" method="post">
                        <input hidden name="id" value="<?php echo $vo['id']; ?>">
                        <input type="submit" value="取消订单">
                    </form>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <?php endif; ?>
        </table>
    </div>

    <div class="box-footer clearfix text-center">

    </div>
</div>
