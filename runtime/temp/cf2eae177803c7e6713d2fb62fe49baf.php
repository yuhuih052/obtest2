<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\member\edit_password.html";i:1585716400;s:79:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\edit_btn_group.html";i:1585716400;}*/ ?>
<form action="<?php echo url(); ?>" method="post" class="form_single">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>用户名</label>
              <input class="form-control" name="username" placeholder="请输入用户名" value="<?php echo $info['username']; ?>" disabled="disabled" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>旧密码</label>
              <input class="form-control" name="old_password" placeholder="请输入旧密码" value="" type="password">
            </div>
          </div>
 
          <div class="col-md-6">
            <div class="form-group">
              <label>新密码</label>
              <input class="form-control" name="password" placeholder="请输入新密码" value="" type="password">
            </div>
          </div>
 
          <div class="col-md-6">
            <div class="form-group">
              <label>确认新密码</label>
              <input class="form-control" name="password_confirm" placeholder="请输入确认新密码" value="" type="password">
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
