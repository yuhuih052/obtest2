<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\withdrawl\withdrawl_list.html";i:1594457489;}*/ ?>

<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>申请人</th>
          <th>账户奖金币余额</th>
          <th>提现申请</th>
          <th>申请时间</th>
          
          <th>手续费</th>
          <th>累计到账</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['username']; ?></td>
                  <td><?php echo $vo['bonus']; ?></td>
                  <td><?php echo $vo['re_withdrawl']; ?></td>
                  <td><?php echo $vo['request_time']; ?></td>
                  <td><?php echo $vo['daozhang']; ?></td>
                  
                  <td class="col-md-2 text-center">
                    <ob_link><a href="<?php echo url('withdrawlAgree', array('id' => $vo['id'],'withdrawl' => $vo['re_withdrawl'])); ?>" id="btn"><button type="submit" class='badge bg-red'>同意</button></a></ob_link>
                      &nbsp;
                      <ob_link><a href="<?php echo url('withdrawlAgreenot', array('id' => $vo['id'],'withdrawl' => $vo['re_withdrawl'])); ?>" id="btn"><button type="submit" class='badge bg-red'>拒绝</button></a></ob_link>
                      
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
