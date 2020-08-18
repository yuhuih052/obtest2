<?php if (!defined('THINK_PATH')) exit(); /*a:10:{s:67:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\site\site.html";i:1597473095;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\top.html";i:1597638481;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\header.html";i:1585716400;s:34:"../app/common/view/fakeloader.html";i:1585716400;s:77:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\sidebar_left.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\crumbs.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\footer.html";i:1585716400;s:78:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\sidebar_right.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\layout\bottom.html";i:1585716400;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>OneBase <?php if(!(empty($ob_title) || (($ob_title instanceof \think\Collection || $ob_title instanceof \think\Paginator ) && $ob_title->isEmpty()))): ?> | <?php echo $ob_title; endif; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="__STATIC__/module/admin/ext/adminlte/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/adminlte/dist/css/skins/_all-skins.css">
    <link rel="stylesheet" href="__STATIC__/module/common/toastr/toastr.min.css">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/btnloading/dist/ladda-themeless.min.css">
    <link rel="stylesheet" href="__STATIC__/module/admin/css/onebase.css">
    <link rel="stylesheet" type="text/css" href="__STATIC__/module/admin/ext/remodal/remodal.css" media="all">
    <link rel="stylesheet" type="text/css" href="__STATIC__/module/admin/ext/remodal/remodal-default-theme.css" media="all">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/adminlte/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="__STATIC__/module/admin/ext/adminlte/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="/static/module/admin/layui/css/layui.css" media="all">
    
    <script src="__STATIC__/index/js/jquery.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/common/jquery/jquery-2.2.3.min.js"></script>
    <script src="__STATIC__/module/admin/ext/jquerypjax/jquery.pjax.js"></script>
    <script src="__STATIC__/module/admin/ext/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="__STATIC__/module/admin/js/init.js"></script>
    <script src="/static/index/sentsin-layui-master/layui/dist/layui.js"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="fakeloader"></div>
<link href="__STATIC__/module/common/fakeloader/css/fakeLoader.css" rel="stylesheet">
<script src="__STATIC__/module/common/fakeloader/js/fakeLoader.min.js"></script>
<script type="text/javascript">
    
    $(".fakeloader").fakeLoader({
        timeToHide:99999,
        bgColor:"rgba(52, 52, 52, .0)",
        spinner:"spinner<?php echo $loading_icon; ?>"
    });
    
    $('.fakeloader').hide();
    
    var pjax_mode    = "<?php echo $pjax_mode; ?>";
    var static_root  = "<?php echo $static_root; ?>";
</script>
<script src="__STATIC__/module/admin/js/init_body.js"></script>
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo url('Index/index'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>OB</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>OneBase</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">导航开关</span>
      </a>
      
        <?php if(!IS_MOBILE): if(is_array($auth_menu_list) || $auth_menu_list instanceof \think\Collection || $auth_menu_list instanceof \think\Paginator): $i = 0; $__LIST__ = $auth_menu_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['is_shortcut'] == '1'): ?>
                    <a href="<?php echo url($vo['url']); ?>" class="sidebar-btn">
                      <?php if(!(empty($vo['icon']) || (($vo['icon'] instanceof \think\Collection || $vo['icon'] instanceof \think\Paginator ) && $vo['icon']->isEmpty()))): ?><i class="fa <?php echo $vo['icon']; ?>"></i><?php endif; ?> <?php echo $vo['name']; ?>
                    </a>
                <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
<!--          <li>
            <a href="<?php echo url('chat/index'); ?>">
              <i class="fa fa-comments"></i>
              <span class="label label-danger new_message_num"></span>
            </a>
          </li>-->
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
<!--            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>-->
            <ul class="dropdown-menu">
              <li class="header">您有10个通知</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 今天有5个新成员加入
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> 这是一条系统警告通知。
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 销售了25个产品喔
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> 用户名修改通知
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">查看所有通知</a></li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo get_head_picture_url($member_info['head_img_id']); ?>" class="user-image" alt="OneBase">
              <span class="hidden-xs"><?php echo $member_info['nickname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo get_head_picture_url($member_info['head_img_id']); ?>" class="img-circle" alt="OneBase">

                <p>
                    <?php echo $member_info['nickname']; ?>
                  <small>上次登录时间：<?php echo $member_info['update_time']; ?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="javascript:;" class="btn clear_cache" url="<?php echo url('login/clearCache'); ?>">清理缓存</a>
                  <a href="<?php echo url('member/editPassword'); ?>" class="btn">修改密码</a>
                  <a href="javascript:;" class="btn logout" url="<?php echo url('login/logout'); ?>">安全退出</a>
                </div>
              </li>
            </ul>
          </li>
          
          <!-- 控制栏切换按钮 -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- 左侧导航栏 -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo get_head_picture_url($member_info['head_img_id']); ?>" class="img-circle" style="height: 45px; width: 45px;" alt="OneBase">
        </div>
        <div class="pull-left info">
            <p><?php echo $member_info['nickname']; ?></p>
            <?php echo $member_info['update_time']; ?>
          <!--<a href="#"><i class="fa fa-circle text-success"></i> 在线状态</a>-->
        </div>
      </div>
      
      
      <!-- search form -->
<!--      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="请输入搜索内容...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      
      <!-- 左侧菜单 -->
      <ul class="sidebar-menu">
        <?php echo $menu_view; ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        
        <section class="content-header">
          <h1>
            <?php if(!(empty($ob_title) || (($ob_title instanceof \think\Collection || $ob_title instanceof \think\Paginator ) && $ob_title->isEmpty()))): ?><?php echo $ob_title; endif; if(!(empty($ob_describe) || (($ob_describe instanceof \think\Collection || $ob_describe instanceof \think\Paginator ) && $ob_describe->isEmpty()))): ?><small><?php echo $ob_describe; ?></small><?php endif; ?>
          </h1>
          <?php echo $crumbs_view; ?>
        </section>
<div class="box">
  <div class="box-header">
    
    <ob_link><a class="btn" id="btn" > 电子币利息转保管金</a></ob_link>
    
  </div>
    
  <div class="box-body table-responsive">

    <table  class="table table-bordered table-hover table-striped">
      <form action="<?php echo url('Site/siteSys'); ?>" method="post">
          <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
      <thead>
      <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <tr>
          <th>系统开关</th>
          <th>提现开关</th>
          <th>最低提现(当前系统：<?php echo $vo['withdrawl_min']; ?>)</th>
          <th>最高提现(当前系统：<?php echo $vo['withdrawl_max']; ?>)</th>
          <th>提现倍率(当前系统:<?php echo $vo['withdrawl_mult']; ?>)</th>
          <th>提现手续费（当前系统：<?php echo $vo['withdrawl_server']; ?>）</th>
          <th>最低充值(当前系统:<?php echo $vo['recharge_min']; ?>)</th>
          <th>最高充值(当前系统：<?php echo $vo['recharge_max']; ?>)</th>
          <th>充值倍率(当前系统:<?php echo $vo['recharge_mult']; ?>)</th>
          <th>订单超时时间(小时)</th>
      </tr>
      </thead>

        <tbody>
                <tr>
                  <?php if($vo['sys_status'] ==1): ?>
                  <td>
                    <label><input name="sys_status" type="radio" value="0" />关 </label>
                      <label><input name="sys_status" checked="true"type="radio" value="1" />开 </label>
                  </td>
                  <?php else: ?>
                  <td>
                    <label><input name="sys_status" type="radio"checked="true" value="0" />关 </label>
                      <label><input name="sys_status" type="radio" value="1" />开 </label>
                  </td>
                  <?php endif; if($vo['withdrawl_switch'] == 1): ?>
                  <td><label><input name="withdrawl_switch" type="radio" value="0" />关 </label>
                      <label><input name="withdrawl_switch"checked="true" type="radio" value="1" />开 </label>
                  </td>
                  <?php else: ?>
                  <td><label><input name="withdrawl_switch" type="radio"checked="true" value="0" />关 </label>
                      <label><input name="withdrawl_switch" type="radio" value="1" />开 </label>
                  </td>
                  <?php endif; ?>
                  <td><input type="number" style="width:120px;" name="withdrawl_min"  value="<?php echo $vo['withdrawl_min']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="withdrawl_max"  value="<?php echo $vo['withdrawl_max']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="withdrawl_mult"  value="<?php echo $vo['withdrawl_mult']; ?>"></td>
                  <td><input type="number" style="width: 120px;" name="server"   value="<?php echo $vo['withdrawl_server']; ?>">%</td>
                  <td><input type="number" style="width:120px;" name="recharge_min"  value="<?php echo $vo['recharge_min']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="recharge_max"  value="<?php echo $vo['recharge_max']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="recharge_mult"  value="<?php echo $vo['recharge_mult']; ?>"></td>
                  <td><input type="number" style="width:120px;" name="overtime"  value="<?php echo $vo['overtime'] /3600; ?>"></td>
                    <td><input  name="id" hidden value="<?php echo $vo['id']; ?>"></td>
                  <td class="col-md-2 text-center">
                      <button type="submit" class='badge bg-green'>保存</button>
                    </form> 
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php else: ?>
        <tbody><tr class="odd"><td colspan="7" class="text-center" valign="top"><?php echo config('empty_list_describe'); ?></td></tr></tbody>
      <?php endif; ?>
    </table>
    <hr style="width: 100%; size: 2px">
  <table style="margin-top: 20px" class="table table-bordered">
    <form action="" method="post" name="form">
    <tr>
      <td>
        当前成交量(当前系统:<?php echo $list2['deal']; ?>)
      </td>
      <td>
        当前价格(当前系统:<?php echo $list2['cfi_price']; ?>)
      </td>
      <td>
        CFI拆分价格
      </td>
      <td>
        默认涨价成交量(当前系统：<?php echo $list2['default_deal']; ?>)
      </td>
      
      <td>
        股票初始发行总量(当前系统:<?php echo $list2['cfi_total']; ?>)
      </td>
      <td>操作</td>
    </tr>
    <tr>
      <td>
        <input type="number" name="deal" value="<?php echo $list2['deal']; ?>">
      </td>
      <td>
        <input type="number" name="cfi_price" onkeyup="value=value.replace(/[^\d\.]/g,'')" value="<?php echo $list2['cfi_price']; ?>">
      </td>
      <td>
        <input type="number" name="default_price" onkeyup="value=value.replace(/[^\d\.]/g,'')" value="<?php echo $list2['default_price']; ?>">
      </td>
      <td>
        <input type="number" name="default_deal" value="<?php echo $list2['default_deal']; ?>">
      </td>
      
      <td>
        <input type="number" name="cfi_total" value="<?php echo $list2['cfi_total']; ?>">
      </td>
      <td>
        <input type="submit" onclick="save()" value="保存">
        <input type="submit" onclick="split()" value="拆分">
      </td>
    </tr>
    </form>
  </table>
  </div>
  
  <div class="box-footer clearfix text-center">
      
  </div>

</div>

<script type="text/javascript">
 $("#btn").click(function(){
    //发起ajax请求
    $.ajax({
       url:"<?php echo url('refresh_in'); ?>",//注意：这里实际情况应该是根据点击的不同a标签加载不同页面
       success: function(data){
         //将请求回来的内容添加到下面的内容div
         if(data==1){
          alert('电子币利息发放成功!')
         }
       }
    });
  });
  function save() {
        document.form.action = "<?php echo url('Site/cfi_deal'); ?>";
        document.form.submit();
    }
    function split(){
        document.form.action="<?php echo url('Site/splitCfi'); ?>";
        document.form.submit();
    }

  function fresh(){  
        if(location.href.indexOf("?reload=true")<0){
            location.href+="?reload=true";  
        }  
    }  
    setTimeout("fresh()",10);
</script>
    </section>
  </div>
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>版本号</b> 1.0.0
    </div>
    <strong>
        版权©2014 - 2016 OneBase .
    </strong>
      保留所有权利。
  </footer>
  
<script src="__STATIC__/module/admin/js/pjax_init.js"></script>
<!--<script src="__STATIC__/module/admin/js/chat.js"></script>-->
  <!-- 控制栏 -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
<!--    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-bell-o"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>-->
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
<!--        <h3 class="control-sidebar-heading">通知开关</h3>
        
          <div class="form-group">
            <label class="control-sidebar-subheading">
              异常登录是否通知
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              不在常用地区或常用IP登录是否通知用户，默认为是。
            </p>
          </div>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              行为异常是否限制
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              用户行为异常是否限制其操作，默认为是。
            </p>
          </div>-->
        
      </div>
      
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
<!--        <form method="post">
          <h3 class="control-sidebar-heading">系统开关</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              是否允许注册
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              若勾选后则不允许用户注册，默认为是。
            </p>
          </div>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              是否调试模式
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              若为调试模式页面将显示Trace信息，默认为是。
            </p>
          </div>
        </form>-->
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="__STATIC__/module/admin/ext/adminlte/dist/js/app.min.js"></script>
<script src="__STATIC__/module/admin/ext/adminlte/dist/js/init.js"></script>
<script src="__STATIC__/module/common/toastr/toastr.min.js"></script>
<script src="__STATIC__/module/admin/ext/btnloading/dist/spin.min.js"></script>
<script src="__STATIC__/module/admin/ext/btnloading/dist/ladda.min.js"></script>
<script src="__STATIC__/module/admin/ext/remodal/remodal.min.js"></script>
<script src="__STATIC__/module/admin/ext/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="__STATIC__/module/admin/js/onebase.js"></script>
<link rel="stylesheet" href="__STATIC__/module/admin/css/ob_skin.css">
<?php echo hook('hook_view_admin'); ?>
</body>
</html>