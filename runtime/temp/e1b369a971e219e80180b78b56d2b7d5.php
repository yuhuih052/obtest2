<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\cfi\dealDetail.html";i:1597655820;}*/ ?>

<div class="box">
 
    
  <div class="box-body table-responsive">
    
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>买家</th>
          <th>卖家</th>
          <th>交易量</th>
          <th>时间</th>
          <th>状态</th>
          <th>操作</th>
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
                  <?php if($vo['seller_id'] == 0): ?>
                  <td>与系统交易</td>
                  <?php else: ?>
                  <td>
                    <?php 
                    $mem = db('member') -> getById( $vo -> seller_id ) ;
                     ?>
                    <?php echo $mem['username']; ?>
                  </td>
                  <?php endif; ?>
                  <td><?php echo $vo['deal_price']; ?></td>
                  <td><?php echo $vo['deal_number']; ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                  
                  
                </tr>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php endif; ?>
    </table>
  </div>

  <div class="box-footer clearfix text-center">
      
  </div>

<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
</div>
