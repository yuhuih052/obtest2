<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\cfi\buycfi.html";i:1597712873;}*/ ?>

<div class="box">
  <!-- <div class="box-header">
    <ob_link><a class="btn" id="btn"><i class="fa fa-plus"></i> 刷新</a></ob_link>
  </div>
  <div>
    <form action="searchCashier" method="post">
      <input type="date" name="date" value="">
      <button type="submit">查询</button>
    </form>
  </div> -->
    
  <div class="box-body table-responsive">
    
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>用户</th>
          <th>总挂买</th>
          <th>剩余交易量</th>
          <th>时间</th>
          <th>状态</th>
          <th>操作</th>
      </tr>
      </thead>
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['user_name']; ?></td>
                  <td><?php echo $vo['buy']; ?></td>
                  <td><?php echo $vo['dianzibi']; ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                  <td>
                    <?php if($vo['statuss'] == 1): ?>挂买中
                    <?php elseif($vo['statuss'] == 2): ?>交易完成
                    <?php elseif($vo['statuss'] == 3): ?>交易关闭
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?php echo url('cfi/dealdetail',['id'=>$vo['id']]); ?>">详情</a>
                  </td>
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
