<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\member\member_edit.html";i:1595325314;s:79:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\edit_btn_group.html";i:1585716400;}*/ ?>
<form action="<?php echo url(); ?>" method="post" class="form_single">
    <div class="box">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>用户名</label>
              <span>（用户名，主要用于登录）</span>
              <input class="form-control" name="username" placeholder="请输入用户名" value="<?php echo $info['username']; ?>" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>昵称</label>
              <span>（会员昵称，主要用于显示）</span>
              <input class="form-control" name="nickname" placeholder="请输入昵称" value="<?php echo $info['nickname']; ?>" type="text">
            </div>
          </div>
 
          <div class="col-md-6">
            <div class="form-group">
              <label>邮箱</label>
              <span>（用户邮箱，用于找回密码等安全操作）</span>
              <input class="form-control" name="email" placeholder="请输入邮箱" value="<?php echo $info['email']; ?>" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>激活/锁定</label>
              &nbsp
              &nbsp
              <span>当前状态：
                <?php if($info['status'] == 0): ?>未激活
                <?php elseif($info['status'] == 1): ?>已激活(已开启)
                <?php elseif($info['status'] == 2): ?>锁定
                <?php endif; ?>
              </span>
              <select id="status" name="status" class="form-control">
                
                <option value="0">操作：未激活</option>
                <option value="1">操作：激活</option>
                <option value="1">操作：开启会员</option>
                <option value="2">操作：锁定会员</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>报单中心</label>
              &nbsp
              &nbsp
              <span>当前状态：
                <?php if($info['is_center'] == 0): ?>否
                <?php elseif($info['is_center'] == 1): ?>是
                <?php elseif($info['is_center'] == 2): ?>申请中
                <?php endif; ?>
              </span>
              <select id="is_center" name="is_center" class="form-control">
                <option value="1">操作：开启报单中心资格</option>
                <option value="0">操作：取消报单中心资格</option>
                <option value="0">操作：拒绝申请</option>
                
              </select>
            </div>
          </div>
              
 
          <div class="col-md-6">
            <div class="form-group">
              <label>手机</label>
              <span>（手机号码，用于找回密码等安全操作）</span>
              <input class="form-control" name="mobile" placeholder="请输入手机号码" value="<?php echo $info['mobile']; ?>" type="text">
            </div>
          </div>
            
          <div class="col-md-6">
            <div class="form-group">
                <label>头像图片</label>
                <br/>
                <?php $head_img_id = (isset($info['head_img_id']) && ($info['head_img_id'] !== '')?$info['head_img_id']:'0'); ?>
                <?php echo widget('file/index', ['name' => 'head_img_id', 'value' => $head_img_id, 'type' => 'img']); ?>
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
<script type="text/javascript">  
     var ss = <?php echo $info['status']; ?>;
     $(" select option[value='"+ss+"']").attr("selected","selected");
     var sss = <?php echo $info['is_center']; ?>;
     $(" select option[value='"+sss+"']").attr("selected","selected");
</script> 