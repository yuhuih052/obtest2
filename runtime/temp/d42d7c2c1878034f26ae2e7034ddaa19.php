<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\blogroll\blogroll_edit.html";i:1585716400;s:78:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\layout\edit_btn_group.html";i:1585716400;}*/ ?>
<form action="<?php echo url(); ?>" method="post" class="form_single">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>链接名称</label>
              <span>（友情链接名称，用于前端展示）</span>
              <input class="form-control" name="name" placeholder="请输入友情链接名称" value="<?php echo (isset($info['name']) && ($info['name'] !== '')?$info['name']:''); ?>" type="text">
            </div>
          </div>
            
          <div class="col-md-6">
            <div class="form-group">
              <label>排序</label>
              <input class="form-control" name="sort" placeholder="请输入排序值" value="<?php echo (isset($info['sort']) && ($info['sort'] !== '')?$info['sort']:'0'); ?>" type="text">
            </div>
          </div> 
            
          <div class="col-md-6">
            <div class="form-group">
              <label>链接URL</label>
              <span>（例如：https://demo.onebase.org）</span>
              <input class="form-control" name="url" placeholder="请输入链接URL" value="<?php echo (isset($info['url']) && ($info['url'] !== '')?$info['url']:''); ?>" type="text">
            </div>
          </div>
            
            
          <div class="col-md-6">
            <div class="form-group">
                <label>链接描述</label>
                <span>（此友情链接的描述信息）</span>
                <textarea class="form-control" name="describe" rows="3" placeholder="请输入链接描述"><?php echo (isset($info['describe']) && ($info['describe'] !== '')?$info['describe']:''); ?></textarea>
            </div>
          </div>
            
            
          <div class="col-md-6">
            <div class="form-group">
                <label>友情链接图片</label>
                <span class="">（请上传单张图片）</span>
                <br/>
                <?php $img_id = (isset($info['img_id']) && ($info['img_id'] !== '')?$info['img_id']:'0'); ?>
                <?php echo widget('file/index', ['name' => 'img_id', 'value' => $img_id, 'type' => 'img']); ?>
            </div>
          </div>
            
          </div>
        </div>
        
      <div class="box-footer">
          
        <input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:'0'); ?>"/>
        
        <button  type="submit" class="btn ladda-button ajax-post" data-style="slide-up" target-form="form_single">
    <span class="ladda-label"><i class="fa fa-send"></i> 确 定</span>
</button>

<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
      </div>
        
      </div>

</form>