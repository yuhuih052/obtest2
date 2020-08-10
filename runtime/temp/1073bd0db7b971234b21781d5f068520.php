<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\fileclean\file_clear.html";i:1585716400;}*/ ?>
<div class="callout callout-warning">
    <h4>注意：（文件清理）点击开始清理后系统目录upload下的图片与文件没有与图片或文件数据表关联的会被自动清理掉</h4>
    <h4>注意：（数据清理）点击开始清理后系统会自动清理数据表（若没有在设置 系统管理->系统设置->图片字段配置 文件数据会被清理掉）</h4>
</div>

<div class="box">
  <div class="box-header">
    
    <ob_link><a id="file_clear" class="btn" url="<?php echo url('cleanList'); ?>"><i class="fa fa-close"></i> 开始清理</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
        <tr>
            <th>文件路径</th>
            <th>文件大小</th>
            <th>创建时间</th>
        </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $vo['file_path']; ?>\<?php echo $vo['file_name']; ?></td>
                    <td><?php echo $vo['file_size']; ?></td>
                    <td><?php echo $vo['file_ctime']; ?></td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="3" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
  </div>
</div>

<script type="text/javascript">

    function sendUrlRequest(id)
    {
        $('.fakeloader').show();

            $.post( $("#" + id).attr("url"),{}, function(data){

                $('.fakeloader').hide();

                obalert(data);

            },"json"
        );
    }
    
    $("#file_clear").click(function(){ sendUrlRequest('file_clear'); });
</script>