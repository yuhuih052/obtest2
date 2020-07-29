<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\member\request_is_center_list.html";i:1595555151;}*/ ?>

<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>申请人</th>
          <th>申请报单中心</th>
        
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['username']; ?></td>

                  <td>
                    <?php if($vo['re_is_center'] == 2): ?>申请二级报单中心
                    <?php elseif($vo['re_is_center'] == 1): ?>申请一级报单中心
                    <?php elseif($vo['re_is_center'] == 3): ?>申请商务报单中心
                    <?php endif; ?>
                  </td>
                  
                  <td class="col-md-2 text-center">
                      <ob_link><a href="<?php echo url('requestAgree', array('id' => $vo['id'],'re_is_center'=>$vo['re_is_center'])); ?>" class="btn"><i class="fa fa-edit"></i> 同意</a></ob_link>
                      &nbsp;
                      <ob_link><a href="<?php echo url('requestDisagreen', array('id' => $vo['id'])); ?>" class="btn"><i class="fa fa-edit"></i> 拒绝</a></ob_link>
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