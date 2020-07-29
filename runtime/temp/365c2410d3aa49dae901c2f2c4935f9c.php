<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\member\member_list.html";i:1595062096;}*/ ?>
<div class="box">
    
  <div class="box-header">

    <div class="row">
        <div class="col-sm-5">
            <ob_link><a class="btn" href="<?php echo url('memberAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
            &nbsp;
            <ob_link><a class="btn export" url="<?php echo url('exportMemberList'); ?>"><i class="fa fa-download"></i> 导 出</a></ob_link>
        </div>
        
        <div class="col-sm-7">
            <div class="box-tools search-form pull-right">
                <div class="input-group input-group-sm">
                    
                    <input type="text" name="search_data" style="width: 200px;" class="form-control pull-right" value="<?php echo input('search_data'); ?>" placeholder="支持昵称|用户名|邮箱|手机">

                    <div class="input-group-btn">
                      <button type="button" id="search"  url="<?php echo url('memberlist'); ?>" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    </div>

                </div>
           </div>
        </div>
    </div>
    
  </div>
    
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>头像</th>
          <th>昵称</th>
          <th>用户名</th>
          <th>邮箱</th>
          <th>手机</th>
          <th>注册时间</th>
          <th>激活状态</th>
          <th>上级</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td>
                      <img class="img-circle" style="width: 30px; height: 30px;" src="<?php echo get_head_picture_url($vo['head_img_id']); ?>"/>
                  </td>
                  <td><?php echo $vo['nickname']; ?></td>
                  <td><?php echo $vo['username']; ?></td>
                  <td><?php echo (isset($vo['email']) && ($vo['email'] !== '')?$vo['email']:'未绑定'); ?></td>
                  <td><?php echo (isset($vo['mobile']) && ($vo['mobile'] !== '')?$vo['mobile']:'未绑定'); ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                  <td>
                    <?php if($vo['status'] == 0): ?>未激活
                <?php elseif($vo['status'] == 1): ?>已激活(已开启)
                <?php elseif($vo['status'] == 2): ?>锁定
                <?php endif; ?>
                  </td>
                  <td><?php echo $vo['leader_nickname']; ?></td>
                  <td class="text-center">
                      <ob_link><a href="<?php echo url('memberEdit', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>编 辑</span></a></ob_link>
                      &nbsp;
                      <ob_link><a href="<?php echo url('memberAuth', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>授 权</span></a></ob_link>
                      &nbsp;
                      <ob_link><a class="confirm ajax-get"  href="<?php echo url('memberDel', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>删 除</span></a></ob_link>
                  </td>
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

<script>
    //导出功能
    $(".export").click(function(){
        
        window.location.href = searchFormUrl($(".export"));
    });
</script>