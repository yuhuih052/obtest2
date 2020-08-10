<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\messages\messages_list.html";i:1596879306;}*/ ?>
<div class="box">
    
  <div class="box-header">

    <div class="row"> 
        
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
          <th>编号</th>
          <th>留言信息</th>
          <th>留言时间</th>
          <th>回复</th>
          <th>回复时间</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['username']; ?></td>
                  <td><?php echo $vo['message']; ?></td>
                  <td><?php echo $vo['message_time']; ?></td>
                  <td>
                    <form action="<?php echo url('responseEdit', ['id' => $vo['id']]); ?>" method="post">
                      <textarea name="response" placeholder="<?php echo $vo['response']; ?>"></textarea>
                      
                    
                  </td>
                  <td><?php echo $vo['response_time']; ?></td>
                  <td class="text-center">
                      <button type="submit" class='badge bg-green'>回复</button>
                  </td>
                  </form>
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

