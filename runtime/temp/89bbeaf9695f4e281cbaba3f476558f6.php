<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\site\site.html";i:1594428885;}*/ ?>
<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" href="<?php echo url('blogrollAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">

    <table  class="table table-bordered table-hover table-striped">
      <form action="<?php echo url('site/siteSys'); ?>" method="post">
          <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
      <thead>
      <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <tr>
          <th>系统开关<?php echo $vo['sys_status']; ?></th>
          <th>提现开关<?php echo $vo['withdrawl_switch']; ?></th>
          <th>最低提现(默认：<?php echo $vo['withdrawl_min']; ?>)</th>
          <th>最高提现(默认：<?php echo $vo['withdrawl_max']; ?>)</th>
          <th>提现倍率(默认:<?php echo $vo['withdrawl_mult']; ?>)</th>
          <th>提现手续费（默认：<?php echo $vo['withdrawl_server']; ?>）</th>
          <th>最低充值(默认:<?php echo $vo['recharge_min']; ?>)</th>
          <th>最高充值(默认：<?php echo $vo['recharge_max']; ?>)</th>
          <th>充值倍率(默认:<?php echo $vo['recharge_mult']; ?>)</th>
      </tr>
      </thead>

        <tbody>
                <tr>
                  <td><label><input name="sys_status" type="radio" value="0" />关 </label>
                      <label><input name="sys_status" checked="true"type="radio" value="1" />开 </label>
                      </td>
                  <td><label><input name="withdrawl_switch" type="radio" value="0" />关 </label>
                      <label><input name="withdrawl_switch"checked="true" type="radio" value="1" />开 </label> </td>
                  <td><input type="number" style="width:120px;" name="withdrawl_min"placeholder="默认100"  value="<?php echo $vo['withdrawl_min']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="withdrawl_max"placeholder="默认1000"  value="<?php echo $vo['withdrawl_max']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="withdrawl_mult"placeholder="默认100"  value="<?php echo $vo['withdrawl_mult']; ?>"></td>
                  <td><input type="text" style="width: 120px;" name="server"placeholder="默认0.05" oninput="value=value.replace(/^\D*(\d*(?:\.\d{0,3})?).*$/g, '$1')" value="<?php echo $vo['withdrawl_server']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="recharge_min"placeholder="默认100"  value="<?php echo $vo['recharge_min']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="recharge_max"placeholder="默认1000"  value="<?php echo $vo['recharge_max']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="recharge_mult"placeholder="默认100"  value="<?php echo $vo['recharge_mult']; ?>"></td>
                    <td><input  name="id" hidden value="<?php echo $vo['id']; ?>"></td>
                  <td class="col-md-2 text-center">
                      <button type="submit" class='badge bg-green'>保存</button>
                    </form> 
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