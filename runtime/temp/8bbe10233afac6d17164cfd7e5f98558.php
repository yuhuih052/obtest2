<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\recharge\recharge_record.html";i:1595212899;}*/ ?>

<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>申请人</th>
          <th>充值申请</th>
          <th>申请时间</th>
          <th>状态</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['user_name']; ?></td>
                  <td><?php echo $vo['request']; ?></td>
                  <td><?php echo $vo['request_time']; ?></td>
                  <td>
                    <?php if($vo['result'] == 0): ?>申请中
                      <?php elseif($vo['result'] == 1): ?>已同意
                      <?php else: ?>已删除
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="7" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
  </div>

  <div class="box-footer clearfix text-center">
      
  </div>

</div>