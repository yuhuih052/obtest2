<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\money\money_list.html";i:1597981589;}*/ ?>

<div class="box">
  <div class="box-header">
    <form action="search" method="post">
        <div class="row">
            <div class="col-sm-3">
                查询用户
                <input type="text" style="width: 120px;" name="username" value="" placeholder="输入用户名查询">
            </div>

            <div class="col-sm-3">
                查询类型：
                <select name="type_search">
                    <option value="" >无</option>
                    <option value ="recharge">充值</option>
                    <option value ="withdrawl">提现</option>
                    <option value="transfer">转账</option>
                    <option value="activate" >激活</option>
                    <option value ="tuijian" >推荐奖</option>
                    <option value ="lingdao" >领导奖</option>
                    <option value="duipeng">对碰奖</option>
                    <option value="guanli">管理奖</option>
                    <option value="jiandian" >见点奖</option>
                    <option value="center_bonus">报单中心奖</option>
                    <option value="wallet">报单币流水</option>
                    <option value="bonus">现金币</option>
                    <option value="baoguanjin" >保管金</option>
                </select>
            </div>
            <div class="col-sm-3">
                日期：
                <input type="date" style="width: 180px;" name="date" value="">
                之后
            </div>
            <div class="col-sm-3">
                <button type="submit">查询</button>
            </div>
        </div>

    </form>

  </div>
    
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>账单用户</th>
          <th>与奖金产生有关的用户</th>
          <th>充值</th>
          <th>提现</th>
          <th>转账</th>
          <th>激活</th>
          <th>推荐奖</th>
          <th>领导奖</th>
          <th>对碰奖</th>
          <th>管理奖</th>
          <th>见点奖</th>
          <th>报单中心奖</th>
          <th>报单币流水</th>
          <th>现金币流水</th>
          <th>保管金流水</th>
          <th>电子币</th>
          <th>CFI</th>
          <th>激活会员系统入账</th>
          <th>说明</th>
          <th>操作时间</th>
      </tr>
      </thead>
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['user_name']; ?></td>
                  <td><?php echo $vo['bonus_user_name']; ?></td>
                  <td><?php echo $vo['recharge']; ?></td>
                  <td><?php echo $vo['withdrawl']; ?></td>
                  <td><?php echo $vo['transfer']; ?></td>
                  <td><?php echo $vo['activate']; ?></td>
                  <td><?php echo $vo['tuijian']; ?></td>
                  <td><?php echo $vo['lingdao']; ?></td>
                  <td><?php echo $vo['duipeng']; ?></td>
                  <td><?php echo $vo['guanli']; ?></td>
                  <td><?php echo $vo['jiandian']; ?></td>
                  <td><?php echo $vo['center_bonus']; ?></td>
                    <td><?php echo $vo['wallet']; ?></td>
                    <td><?php echo $vo['bonus']; ?></td>
                    <td><?php echo $vo['baoguanjin']; ?></td>
                    <td><?php echo $vo['dianzibi_all']; ?></td>
                    <td><?php echo $vo['CFI']; ?></td>
                    <td><?php echo $vo['baodanbi_all']; ?></td>
                    <td><?php echo $vo['shuoming']; ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                </tr>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php endif; ?>
    </table>
  </div>

  <div class="box-footer clearfix text-center">
      <?php echo $list->render(); ?>
  </div>
    <a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>


</div>