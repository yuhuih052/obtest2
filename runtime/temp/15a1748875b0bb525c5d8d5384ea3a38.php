<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\withdrawl\withdrawl_record.html";i:1594458679;}*/ ?>

<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>申请人</th>
          <th>提现申请</th>
          <th>申请时间</th>
          
          <th>手续费</th>
          <th>累计到账</th>
          <th>处理结果</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['user_name']; ?></td>
                  <td><?php echo $vo['amount']; ?></td>
                  <td><?php echo $vo['time']; ?></td>
                  <td><?php echo $vo['server']; ?></td>
                  <td><?php echo $vo['daozhang']; ?></td>
                  <td><?php echo $vo['shuoming']; ?></td>
                  
                  <td class="col-md-2 text-center">
                     <!--  <ob_link><a href="<?php echo url('withdrawldelete', array('id' => $vo['id'])); ?>" id="btn"class="btn"><i class="fa fa-edit"></i> 删除</a></ob_link>
                      &nbsp;
                      -->
                      
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="7" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
  </div>

  <div class="box-footer clearfix text-center">
      <?php echo $list->render(); ?>
  </div>

</div>
