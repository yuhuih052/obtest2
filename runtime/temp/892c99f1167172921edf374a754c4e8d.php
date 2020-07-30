<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\user\index.html";i:1596070873;s:64:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout.html";i:1585716400;s:68:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\top.html";i:1585716400;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\header.html";i:1595917326;s:71:"D:\phpstudy_pro\WWW\obtest2\public/../app/index\view\layout\footer.html";i:1585716400;}*/ ?>
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>注册</title>
    <link rel="stylesheet" href="__STATIC__/index/css/mui.min.css"/>
    <link rel="stylesheet" href="__STATIC__/index/fonts/iconfont.css" />
    <link rel="stylesheet" href="__STATIC__/index/css/style.css" />
</head>
<body>
<header class="mui-bar mui-bar-nav headernav-bg">
    <a href="javascript:window.history.go(-1);" class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left header-a1"></a>
    <h1 class="mui-title header-h1">个人中心</h1>

</header>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        会员管理
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href=" <?php echo url('user/re_tree',['id'=>$data['id']]); ?>">  推荐关系图</a></li>
        <li><a href="<?php echo url('user/system'); ?>">双区结构图</a></li>
        <li><a href=" <?php echo url('user/userInfoEdit',['id'=>$data['id']]); ?>">修改资料</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        报单中心管理
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <?php if($data['is_center'] > 0): ?>
        <li><a href=" <?php echo url('user/userRecommonder',['username'=>$data['username']]); ?>">激活会员</a></li>
        <li><a id="" href="<?php echo url('user/zhuanzhang'); ?>">转账</a></li>
        <?php endif; ?>
        <li>当前报单等级：
            <?php if($data['is_center'] == 0): ?>无
            <?php elseif($data['is_center'] == 1): ?>二级
            <?php elseif($data['is_center'] == 2): ?>一级
            <?php elseif($data['i_center'] == 3): ?>商务
            <?php endif; ?>
        </li>
        <?php if($data['is_center'] == 0): ?>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>1,'re_is_center'=>1]); ?>">申请二级报单中心</a></li>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>2,'re_is_center'=>2]); ?>">申请一级报单中心</a></li>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>3,'re_is_center'=>3]); ?>">申请商务报单中心</a></li>
        <?php elseif($data['re_is_center'] > 0): ?>
        <li><a href="#">正在申请成为报单中心</a></li>
        <?php elseif($data['is_center'] == 1): ?>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>2,'re_is_center'=>2]); ?>">申请一级报单中心</a></li>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>3,'re_is_center'=>3]); ?>">申请商务报单中心</a></li>
        <?php elseif($data['is_center'] == 2): ?>
        <li><a href="<?php echo url('user/request_is_center',['is_center'=>3,'re_is_center'=>3]); ?>">申请商务报单中心</a></li>
        <?php elseif($data['is_center'] == 3): ?>
        <li><a href="#">已成为商务报单中心</a></li>
        <?php endif; ?>
      </ul>
            
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        财务管理
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href="<?php echo url('user/chongzhi'); ?>">  充值报单币《《</a></li>
        <li><a href="<?php echo url('user/zhuanhuan'); ?>">货币转换</a></li>
        <li><a href="#">升级</a></li>
        <li><a href="#">复投</a></li>
        <li><a href="<?php echo url('user/billDetail'); ?>">货币明细</a></li>
        <li><a href="#">奖金明细</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        现金币交易
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href="#">挂卖市场</a></li>
        <li><a href="#">匹配订单</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        CFI交易
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href="">买入CFI</a></li>
        <li><a href="#">卖出CFI</a></li>
        <li><a href="">买入记录</a></li>
        <li><a href="#">卖出记录</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        资讯管理
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href="<?php echo url('index/index'); ?>">新闻公告</a></li>
        <!-- <li><a href="<?php echo url('user/message'); ?>">写留言</a></li> -->
        <li><a href="<?php echo url('message/witer'); ?>">写留言</a></li>
        <li><a href="<?php echo url('message/receiveMessage'); ?>">收件箱</a></li>
        <li><a href="<?php echo url('message/sendMessage'); ?>">发件箱</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        会员升级
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>当前等级：<?php echo $data['member_rank']; ?></li>
        <li><a href="<?php echo url('user/upgrade',['v'=>2]); ?>">升级为二星</a></li>
        <li><a href="<?php echo url('user/upgrade',['v'=>3]); ?>">升级为三星</a></li>
        <li><a href="<?php echo url('user/upgrade',['v'=>4]); ?>">升级为四星</a></li>
        <li><a href="<?php echo url('user/upgrade',['v'=>5]); ?>">升级为五星</a></li>
         <li><a href="<?php echo url('user/upgrade',['v'=>6]); ?>">升级为六星</a></li>
      </ul>
    </div>
    <div class="dropdown" style="margin-top: 10px">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        复投
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
       <?php if($data['master_id'] == Null): ?>
        <li><a href="<?php echo url('futou/list'); ?>">子账号列表</a></li>
        
        <?php else: ?>
        <li><a href="<?php echo url('futou/returnInto'); ?>">返回主账号</a></li>
        <?php endif; ?>
      </ul>
    </div>


    <div class="content-view">
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>用户名</label></div>
            <div class="mui-col-xs-8"><?php echo $data['username']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>推荐人</label></div>
            <div class="mui-col-xs-8"><?php echo $data['Re_name']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>接点人</label></div>
            <div class="mui-col-xs-8"><?php echo $data['father_name']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>会员编号</label></div>
            <div class="mui-col-xs-8"><?php echo $data['member_number']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>手机号</label></div>
            <div class="mui-col-xs-8"><?php echo $data['mobile']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>银行名称</label></div>
            <div class="mui-col-xs-8"><?php echo $data['bankname']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>银行卡号</label></div>
            <div class="mui-col-xs-8"><?php echo $data['bankcard']; ?></div>
        </div>

        
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>会员编号</label></div>
            <div class="mui-col-xs-8"><?php echo $data['member_number']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>登录密码</label></div>
            <div class="mui-col-xs-8"><input type="password" name="password" class="code_input" value="<?php echo $data['password']; ?>" placeholder=""></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保问题</label></div>
            <div class="mui-col-xs-8" ><?php echo $data['mibaowenti']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>密保答案</label></div>
            <div class="mui-col-xs-8"><?php echo $data['mibaodaan']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>申请充值报单币</label></div>
            <div class="mui-col-xs-8"><?php echo $data['request_chongzhi']; ?>(审核中)</div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>报单币</label></div>
            <div class="mui-col-xs-8"><?php echo $data['wallet']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>奖金币</label></div>
            <div class="mui-col-xs-8"><?php echo $data['bonus']; ?></div>
        </div>
        <div class="mui-row code_cell">
            <div class="mui-col-xs-4"><label>提现申请</label></div>
            <div class="mui-col-xs-8"><?php echo $data['re_withdrawl']; ?></div>
        </div>
        
        <a href=" <?php echo url('user/userRecommonder',['username'=>$data['username']]); ?>">  直接推荐人列表《《</a>
        <!-- <a href=" <?php echo url('user/my_teamTree',['id'=>$data['id']]); ?>">  推荐关系《《</a> -->
        <!-- <a href=" <?php echo url('members/teamtree',['id'=>$data['id']]); ?>">  我的团队《《</a>
        <a href=" <?php echo url('members/tree',['id'=>$data['id']]); ?>">  测试tree《《</a> -->
        
        <a href=" <?php echo url('user/twoPathway'); ?>">  双区结构图《《</a>
        
        
        
         <a href="<?php echo url('user/transferRecord',['username'=>$data['username']]); ?>">转账记录《</a>
         <a href="<?php echo url('user/withdrawl'); ?>">提现《《</a>
         <a href="<?php echo url('user/withDrawlrecord',['id'=>$data['id']]); ?>">提现记录《《</a>
         <!-- <a href="<?php echo url('user/message'); ?>">留言《《</a> -->
         <a href="<?php echo url('user/messageRecord',['id'=> $data['id']]); ?>">留言记录《《</a>
        <a href="<?php echo url('login/logout'); ?>">  退出《</a>

    </div>

</body>
<script type="text/javascript">
     $(document).ready(function(){
        $("#zhuanzhang").click(function(){
            $.get("user/zhuanzhang",function(data,status){
                if(data==0){
                    alert('您不是报单中心，无法转账操作');
                }
            });      
    });
        $("#")
});
</script>

</html>
<footer class="footer">
  <div class="container">
      <p> 本站由 <strong><a href="http://www.onebase.org" target="_blank">OneBase</a></strong> 强力驱动</p>
  </div>
</footer>

<script type="text/javascript" src="__STATIC__/module/index/js/footer.js"></script>

<?php echo hook('hook_view_index'); ?>

</body>
</html>
