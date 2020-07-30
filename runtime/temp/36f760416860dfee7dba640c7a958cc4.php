<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\bonus\bonus_cashier.html";i:1595206869;}*/ ?>

<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
  <div>
    <form action="searchCashier" method="post">
      <input type="date" name="date" value="">
      <button type="submit">查询</button>
    </form>
  </div>
    
  <div class="box-body table-responsive">
    
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>日期</th>
          <th>拨出</th>
          <th>入账</th>
          <th>拨出比</th>
      </tr>
      </thead>
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['month_day']; ?></td>
                  <td><?php echo $vo['out_account']; ?></td>
                  <td><?php echo $vo['entry']; ?></td>
                  <td><?php echo $vo['pp'] * 100; ?>%</td>
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