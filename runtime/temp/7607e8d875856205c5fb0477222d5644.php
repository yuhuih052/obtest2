<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/install\view\index\step2.html";i:1585716400;s:70:"D:\phpstudy_pro\WWW\obtest\public/../app/install\view\public\base.html";i:1585716400;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OneBase 安装</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="__STATIC__/module/install/css/bootstrap.min.css" rel="stylesheet">
        <link href="__STATIC__/module/install/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="__STATIC__/module/install/css/install.css" rel="stylesheet">
        <script src="__STATIC__/module/install/js/jquery-1.10.2.min.js"></script>
        <script src="__STATIC__/module/install/js/bootstrap.min.js"></script>
    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" target="_blank" href="https://onebase.org">OneBase</a>
                    <div class="nav-collapse collapse">
                    	<ul id="step" class="nav">
                            
    <li><a>安装协议</a></li>
    <li><a>环境检测</a></li>
    <li class="active"><a>创建数据库</a></li>
    <li><a>安装</a></li>
    <li><a>完成</a></li>

                    	</ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                

    <h1>数据库信息</h1>
    <form action="<?php echo url('step2'); ?>" method="post" target="_self">
        <div class="create-database">
            <div>
                <select name="db[]">
                    <option>mysql</option>
                </select>
                <span>数据库类型</span>
            </div>
            <div>
                <input type="text" name="db[]" value="127.0.0.1" placeholder="请输入数据库服务器IP">
                <span>数据库服务器IP，如：127.0.0.1</span>
            </div>
            <div>
                <input type="text" name="db[]" value="onebase" placeholder="请输入数据库名称">
                <span>数据库名称，如：onebase</span>
            </div>
            <div>
                <input type="text" name="db[]" value="root" placeholder="请输入数据库用户名">
                <span>数据库用户名，如：root</span>
            </div>
            <div>
                <input type="password" name="db[]" placeholder="请输入数据库密码">
                <span>数据库密码，若无密码则为空</span>
            </div>
            <div>
                <input type="text" name="db[]" value="3306" placeholder="请输入数据库端口">
                <span>数据库端口，数据库服务连接端口，一般为3306</span>
            </div>

            <div>
                <input type="text" name="db[]" value="ob_" placeholder="请输入数据表前缀">
                <span>数据表前缀</span>
            </div>
        </div>

        <div class="create-database">
            <br/><br/><br/>
            <h2>超级管理员信息</h2>
            <div>
                <input type="text" name="admin[]" placeholder="请输入超级管理员用户名">
                <span>用户名</span>
            </div>
            <div>
                <input type="password" name="admin[]" placeholder="请输入超级管理员密码">
                <span>密码</span>
            </div>
            <div>
                <input type="password" name="admin[]" placeholder="请输入超级管理员确认密码">
                <span>确认密码</span>
            </div>
            <div>
                <input type="text" name="admin[]" placeholder="请输入超级管理员邮箱">
                <span>请填写正确的邮箱便于收取提醒邮件</span>
            </div>
        </div>
    </form>

            </div>
        </div>

        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div style="">
                    
    <a class="btn btn-success btn-large" href="<?php echo url('step1'); ?>">上一步</a>
    <button id="submit" type="button" class="btn btn-primary btn-large" onclick="$('form').submit();return false;">下一步</button>

                </div>
            </div>
        </footer>
    </body>
</html>
