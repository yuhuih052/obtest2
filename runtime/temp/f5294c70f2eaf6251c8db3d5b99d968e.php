<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\member\member_activate.html";i:1595993280;}*/ ?>
<form action="<?php echo url('member/acSave'); ?>" method="post">
	<div class="form-group">
              <label>用户名</label>
              <span>（用户名，主要用于登录）</span>
              <input class="form-control" name="username" readonly value="<?php echo $info['username']; ?>" type="text">
            </div>
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
            <button type="submit" style="margin: 10px">确定</button>
</form>
<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
<script type="text/javascript">  
     var ss = <?php echo $info['status']; ?>;
     $(" select option[value='"+ss+"']").attr("selected","selected");
</script> 