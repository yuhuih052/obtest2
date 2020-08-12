<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\database\data_restore.html";i:1585716400;}*/ ?>
<div class="callout callout-warning">
    <h4>注意：数据还原前请先备份当前数据，防止还原过程出现异常。</h4>
</div>

<div class="box">
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>备份名称</th>
          <th>压缩</th>
          <th>数据大小</th>
          <th>备份时间</th>
          <th>还原状态</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo date('Ymd-His',$vo['time']); ?></td>
                  <td><?php echo $vo['compress']; ?></td>
                  <td><?php echo format_bytes($vo['size']); ?></td>
                  <td><?php echo $key; ?></td>
                  <td>-</td>
                  <td style="width: 200px;">
                      <ob_link><a class="btn ajax-get db-import" href="<?php echo url('dataRestoreHandle?time='.$vo['time']); ?>" class="btn"><i class="fa fa-exchange"></i> 还 原</a></ob_link>
                      &nbsp;
                      <ob_link><a class="btn ajax-get" href="<?php echo url('backupDel?time='.$vo['time']); ?>"><i class="fa fa-trash-o"></i> 删 除</a></ob_link>
                  </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="6" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
  </div>
</div>

<script type="text/javascript">
    $(".db-import").click(function(){
        var self = this, status = ".";
        $.get(self.href, success, "json");
        window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
        return false;

        function success(data){
            
            if(data.status){
                if(data.gz){
                    data.msg += status;
                    if(status.length === 5){
                        status = ".";
                    } else {
                        status += ".";
                    }
                }
                
                $(self).parent().parent().prev().text(data.msg);
                if(data.part){
                    $.get(self.href, 
                        {"part" : data.part, "start" : data.start}, 
                        success, 
                        "json"
                    );
                }  else {
                    window.onbeforeunload = function(){ return null; }
                }
            } else {
                
                toast.warning(data.msg);
            }
        }
    });
</script>