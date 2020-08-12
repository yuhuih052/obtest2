<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\member\member_list.html";i:1597135221;}*/ ?>
<div class="box">
    
  <div class="box-header">

    <div class="row">
        <div class="col-sm-5">
            <ob_link><a class="btn" href="<?php echo url('memberAdd'); ?>"><i class="fa fa-plus"></i> 新 增</a></ob_link>
            &nbsp;
            <ob_link><a class="btn export" url="<?php echo url('exportMemberList'); ?>"><i class="fa fa-download"></i> 导 出</a></ob_link>
        </div>
        
    </div>
    <div class="row">
    <form action="" method="post" name="form" style="margin-top: 10px;">
        <div class="row">
            
            <div class="col-sm-3">
                开始：<input type="date" style="width: 150px;"name="date1" value="">
                结束：<input type="date" style="width: 150px;" name="date2" value="">
                
                <input type="submit" onclick="find_data()" value="时间查询">
            </div>

            <div class="col-sm-3">
                <input type="text" style="width: 150px;"name="Id1" value="" placeholder="ID开始">
                <input type="text" style="width: 150px;" name="Id2" value="" placeholder="ID结束">
                
                <input type="submit" onclick="find_id()" value="ID查询">
            </div>
            <div class="col-sm-3">
              

                查询方式(必选）
                <select name="select">
                  <option value="1">用户名</option>
                  <option value="2">推荐人</option>
                  <option value="3">所属报单中心</option>
                  <!-- <option value="4">投资级别</option> -->
                  
                </select>
                <input type="text" style="width: 150px;" name="search" value="" placeholder="输入查询内容">
                <input type="submit" onclick="find_aa()" value="查询">
                
            </div>
            <div class="col-sm-3">
              

                投资级别
                <select name="select_rank">
                  <option value="1">一星会员</option>
                  <option value="2">二星会员</option>
                  <option value="3">三星会员</option>
                  <option value="4">四星会员</option>
                  <option value="5">五星会员</option>
                  <option value="6">六星会员</option>
                </select>
      
                <input type="submit" onclick="select_rank()" value="查询等级">     
            </div>  
        </div>
    </form>
    </div>
  </div>
    
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>头像</th>
          <th>昵称</th>
          <th>用户名</th>
          <th>ID号</th>
          <th>邮箱</th>
          <th>手机</th>
          <th>总奖金</th>
          <th>现金币</th>
          <th>报单币</th>
          <th>CFI</th>
          <th>电子币</th>
          <th>保管金</th>
          <th>报单中心</th>
          <th>会员等级</th>
          <th>推荐人</th>
          <th>接点人</th>
          <th>激活状态</th>
          <th>上级</th>
          <th>注册时间</th>
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
                  <td><?php echo $vo['member_number']; ?></td>
                  <td><?php echo (isset($vo['email']) && ($vo['email'] !== '')?$vo['email']:'未绑定'); ?></td>
                  <td><?php echo (isset($vo['mobile']) && ($vo['mobile'] !== '')?$vo['mobile']:'未绑定'); ?></td>
                  <td><?php echo $vo['all_bonus']; ?></td>
                  <td><?php echo $vo['bonus']; ?></td>
                  <td><?php echo $vo['wallet']; ?></td>
                  <td><?php echo $vo['CFI']; ?></td>
                  <td><?php echo $vo['dianzibi']; ?></td>
                  <td><?php echo $vo['baoguanjin']; ?></td>
                  <td>
                    <?php if($vo['is_center'] == 0): ?>否
                    <?php elseif($vo['is_center'] == 1): ?>二级报单中心
                    <?php elseif($vo['is_center'] == 2): ?>一级报单中心
                    <?php elseif($vo['is_center'] == 3): ?>商务级报单中心
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if($vo['member_rank'] == 1): ?>一星会员
                    <?php elseif($vo['member_rank'] == 2): ?>二星会员
                    <?php elseif($vo['member_rank'] == 3): ?>三星会员
                    <?php elseif($vo['member_rank'] == 4): ?>四星会员
                    <?php elseif($vo['member_rank'] == 5): ?>五星会员
                    <?php elseif($vo['member_rank'] == 6): ?>六星会员
                    <?php endif; ?>
                  </td>
                  <td><?php echo $vo['Re_name']; ?></td>
                  <td><?php echo $vo['father_name']; ?></td>
                  <td>
                    <?php if($vo['status'] == 0): ?>未激活
                <?php elseif($vo['status'] == 1): ?>已激活(已开启)
                <?php elseif($vo['status'] == 2): ?>锁定
                <?php endif; ?>
                  </td>
                  <td><?php echo $vo['Re_name']; ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                  <td class="text-center">
                      <ob_link><a href="<?php echo url('memberEdit', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>编 辑</span></a></ob_link>
                      &nbsp;
                      <ob_link><a href="<?php echo url('memberActivate', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>激活/锁定操作</span></a></ob_link>
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
   function find_aa(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="searchUser";
        document.form.submit();
    }
    function find_data(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="searchData";
        document.form.submit();
    }
    function find_id(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="searchId";
        document.form.submit();
    }
    function select_rank(){
//        document.("表单的name值").action
//        document.("表单的name值").submit
        document.form.action="searchRank";
        document.form.submit();
    }
    //导出功能
    $(".export").click(function(){
        
        window.location.href = searchFormUrl($(".export"));
    });
</script>