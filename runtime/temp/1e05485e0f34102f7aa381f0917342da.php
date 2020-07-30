<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\messages\responseedit.html";i:1594285242;s:78:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\layout\edit_btn_group.html";i:1585716400;}*/ ?>
<form action="<?php echo url('messages/responseEdit'); ?>" method="post" class="form_single">
      
            <div class="form-group">
              <label>用户名</label>
              <input class="form-control" name="username" readonly value="" type="text">
            </div>
 
          <div class="col-md-6">
            <div class="form-group">
              <label>设置激活状态</label>
              
              <input class="form-control" name="status" placeholder="0为未激活，1为已激活" value="" type="text">（0为未激活，1为已激活）
            </div>
          </div>

      <div class="box-footer">
        
        <input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:'0'); ?>"/>
          
        <button  type="submit" class="btn ladda-button ajax-post" data-style="slide-up" target-form="form_single">
    <span class="ladda-label"><i class="fa fa-send"></i> 确 定</span>
</button>

<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
        
      </div>
    
</form>
