<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\api\api_list.html";i:1585716400;s:79:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\layout\batch_btn_group.html";i:1585716400;}*/ ?>
<div class="box">
  <div class="box-header">
      
    <div class="row">
        <div class="col-sm-4">
            <ob_link><a class="btn" href="<?php echo url('apiAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
            <a class="btn" href="/api.php" target="_blank"><i class="fa fa-book"></i> 接口文档</a>
        </div>
        
        <div class="col-sm-8">
            <div class="box-tools search-form pull-right">
                <div class="input-group input-group-sm">
                    
                    <input type="text" name="search_data" style="width: 200px;" class="form-control pull-right" value="<?php echo input('search_data'); ?>" placeholder="请输入接口名称">

                    <div class="input-group-btn">
                      <button type="button" id="search"  url="<?php echo url('apilist'); ?>" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
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
                <th class="checkbox-select-all">
                    <label>
                      <input class="flat-grey js-checkbox-all" type="checkbox">
                    </label>
                </th>
                <th>名称</th>
                <th>分组</th>
                <th>地址</th>
                <th>进度</th>
                <th>研发者</th>
                <th class="sort-th">排序</th>
                <th class="status-th">状态</th>
                <th>操作</th>
            </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td>
                    <label>
                        <input class="flat-grey" type="checkbox" name="ids" value="<?php echo $vo['id']; ?>">
                    </label>
                  </td>
                  <td><?php echo $vo['name']; ?></td>
                  <td><?php echo $vo['group_name']; ?></td>
                  <td><?php echo $vo['api_url']; ?></td>
                  <td><?php echo $vo['api_status_text']; ?></td>
                  <td><?php echo $vo['developer_text']; ?></td>
                  <td>
                      <input type="text" class="sort-th sort-text" href="<?php echo url('setSort'); ?>" id="<?php echo $vo['id']; ?>" value="<?php echo $vo['sort']; ?>" />
                  </td>
                  <td>
                      <ob_link><a class="ajax-get" href="<?php echo url('setStatus', array('ids' => $vo['id'], 'status' => (int)!$vo['status'])); ?>"><?php echo $vo['status_text']; ?></a></ob_link>
                  </td>
                  <td class="col-md-2 text-center">
                      <ob_link><a href="<?php echo url('apiEdit', array('id' => $vo['id'])); ?>" class="btn"><i class="fa fa-edit"></i> 编 辑</a></ob_link>
                      <ob_link><a class="btn confirm ajax-get" href="<?php echo url('setStatus', array('ids' => $vo['id'], 'status' => DATA_DELETE)); ?>"><i class="fa fa-trash-o"></i> 删 除</a></ob_link>
                  </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="9" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
      
    <div class="lockscreen-footer">
    <ob_link><a class="btn batch_btn confirm ajax-post" value="<?php echo DATA_NORMAL; ?>"  href="<?php echo url('setStatus'); ?>"><i class="fa fa-check-circle"></i> 启 用</a></ob_link>
    <ob_link><a class="btn batch_btn confirm ajax-post" value="<?php echo DATA_DISABLE; ?>" href="<?php echo url('setStatus'); ?>"><i class="fa fa-times-circle"></i> 禁 用</a></ob_link>
    <ob_link><a class="btn batch_btn confirm ajax-post" value="<?php echo DATA_DELETE; ?>"  href="<?php echo url('setStatus'); ?>"><i class="fa fa-trash"></i> 删 除</a></ob_link>
</div>
    
  </div>
  <div class="box-footer clearfix text-center">
      <?php echo $list->render(); ?>
  </div>
</div>