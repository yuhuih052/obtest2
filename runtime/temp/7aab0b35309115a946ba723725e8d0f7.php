<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:74:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\futou\futou_list.html";i:1597458327;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1597397643;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1597721545;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>OneBase开源架构|PHP开源系统</title>
    <meta name="keywords" content="OneBase,PHP开源系统,ThinkPHP5,TP5,PHP框架,PHP源码"/>
    <meta name="description" content="一款基于ThinkPHP5研发的开源免费基础架构，基于OneBase可以快速的研发各类应用。" />
    <link href="__STATIC__/module/common/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="__STATIC__/module/common/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="__STATIC__/module/index/css/docs.css" rel="stylesheet">
    <link href="__STATIC__/module/index/css/onebase.css" rel="stylesheet">
    <link href="__STATIC__/index/layui/css/layui.css" rel="stylesheet">
    
    <script type="text/javascript" src="__STATIC__/module/common/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/common/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/index/js/common.js"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo url('index/home'); ?>">OneBase</a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                <ul class="nav">
                        <li>
                            <a href="<?php echo url('index/home'); ?>">首页</a>
                        </li>
                        <li>
                            <a href="<?php echo url('index/index'); ?>">新闻公告</a>
                        </li>

                        <?php if(\think\Request::instance()->session('user_id2') != null): ?>
                          <li><a href="<?php echo url('user/index',['id'=> \think\Request::instance()->session('user_id2')]); ?>">个人中心</a></li>
                          
                        <?php else: ?>
                         <li>
                        <a target="_blank" href="<?php echo url('login/login'); ?>">登录</a>
                    </li>

                    <li>
                        <a target="_blank" href="<?php echo url('login/register'); ?>">注册</a>
                    </li>
                    <li>
                            <a target="_blank" href="<?php echo URL_ROOT; ?>/admin.php">后台管理</a>
                        </li>
                        <?php endif; ?>


                </ul>
            </div>
        </div>
    </div>
</div>
<div style="margin-top: 50px;" class="box">
    
  <div class="box-header">

    <div class="row"> 
        
        <div class="col-sm-7">
            <div class="box-tools search-form pull-right">
                <div class="input-group input-group-sm">
                    
                    <input type="text" name="search_data" style="width: 200px;" class="form-control pull-right" value="<?php echo input('search_data'); ?>" placeholder="支持昵称|用户名|邮箱|手机">

                    <div class="input-group-btn">
                      <button type="button" id="search"  url="<?php echo url('memberlist'); ?>" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    </div>

                </div>
           </div>
        </div>
    </div>
    
  </div>
    <a  href="<?php echo url('futou/futou'); ?>"><button style="margin-left: 15px;margin-bottom: 15px;">复投</button></a>
    <a  href="<?php echo url('futou/gather'); ?>"><button style="margin-left: 15px;margin-bottom: 15px;">一键收取资金</button></a>
  <div class="box-body table-responsive">
    <table  class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th hidden>头像</th>
          <th>编号</th>
          <th>用户名</th>
          <th>激活时间</th>
          <th>激活状态</th>
          <th>报单中心</th>
          <th>推荐人</th>
          <th>接点人</th>
          <th>现金币</th>
          <th>报单币</th>
          <th>CFI</th>
          <th>保管金</th>
          <th>总奖金</th>
          <th>操作</th>
      </tr>
      </thead>
      
      <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                  <td><?php echo $vo['member_number']; ?></td>
                  <td><?php echo $vo['username']; ?></td>
                  <td><?php echo $vo['create_time']; ?></td>
                    <?php if($vo['status'] == 1): ?>
                    <td>已激活</td>
                    <?php elseif($vo['status'] == 0): ?>
                    <td>未激活</td>
                    <?php elseif($vo['status'] == 2): ?>
                    <td>已锁定</td>
                    <?php endif; ?>
                  <td><?php echo $vo['center_name']; ?></td>
                  <td><?php echo $vo['Re_name']; ?></td>
                  <td><?php echo $vo['father_name']; ?></td>
                  <td><?php echo $vo['bonus']; ?></td>
                  <td><?php echo $vo['wallet']; ?></td>
                  <td><?php echo $vo['CFI']; ?></td>
                  <td><?php echo $vo['baoguanjin']; ?></td>
                  <td><?php echo $vo['all_bonus']; ?></td>
                  <td class="text-center">
                      <ob_link><a href="<?php echo url('futou/getInto', array('id' => $vo['id'])); ?>"><span class='badge bg-green'>进入子账号</span></a></ob_link>

                      &nbsp;
                      <?php if($vo['bonus'] > 0 && $vo['wallet'] > 0): ?>
                      <ob_link><a class="confirm ajax-get"  href="<?php echo url('futou/gatherOne', array('username' => $vo['username'])); ?>"><span class='badge bg-red'>收取资金</span></a></ob_link>
                      <?php endif; ?>
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
      </div>
      <a href="<?php echo url('user/index',['id' => \think\Request::instance()->session('user_id2')]); ?>">个人列表</a>
<a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a>
</div>


<footer class="footer">
  <div class="container">
<!--      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>-->
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
