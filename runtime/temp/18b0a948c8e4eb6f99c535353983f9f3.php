<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\bonus\bonus_statistics.html";i:1594533920;}*/ ?>
<div class="box">
    
  <div class="box-header">

    <div class="row"> 
        <form action="search" method="post">
            <div class="col-sm-4">
                <input type="text" style="width: 120px;" name="username" value="" placeholder="输入用户名查询">
                <button type="submit">查询</button>
            </div>

        </form>
    </div>
    
  </div>
    
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>用户名</th>
          <th>奖金金额</th>
          <th>奖金类型</th>
          <th>获奖时间</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $vo['user_name']; ?></td>
                    <td><?php echo $vo['bonus_amount']; ?></td>
                    <td><?php echo $vo['bonus_type']; ?></td>
                    <td><?php echo $vo['bonus_time']; ?></td>
                  
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="8" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
  </div>

  <div class="box-footer clearfix text-center">
    <?php echo $list->render(); ?>
      </div>
<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
</div>

