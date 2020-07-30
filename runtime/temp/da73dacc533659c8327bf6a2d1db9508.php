<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"D:\phpstudy_pro\WWW\obtest\public/../app/api\view\index\index.html";i:1585716400;s:34:"../app/common/view/fakeloader.html";i:1585716400;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
    <title>OneBase API文档</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="__STATIC__/module/api/css/bootstrap.css" rel="stylesheet">
    <link href="__STATIC__/module/api/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="__STATIC__/module/common/jquery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="__STATIC__/module/api/ext/jqueryform/jquery.form.js"></script>
    </head>
<body>

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

    <div class="container-fluid">
        <div class="row-fluid">
            <div id="sidenav" class="span2">
            <nav id="scrollingNav">
              <ul class="sidenav nav nav-list">
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <li class="nav-header">
                            <a><?php echo $vo['name']; ?></a>
                        </li>
                        <?php if(!(empty($vo['api_list']) || (($vo['api_list'] instanceof \think\Collection || $vo['api_list'] instanceof \think\Paginator ) && $vo['api_list']->isEmpty()))): if(is_array($vo['api_list']) || $vo['api_list'] instanceof \think\Collection || $vo['api_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['api_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                <li>
                                    <a onclick="apiDetails(this)" url="<?php echo url('details', array('id' => $v['id'])); ?>" style="cursor:pointer;"><?php echo $v['name']; ?></a>
                                </li>
                            <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            </nav>
        </div>

        <div id="content">
            <?php echo $content; ?>
        </div>
        </div>
    </div>

    <script type="text/javascript" src="__STATIC__/module/api/js/api.js"></script>

</body>
</html>