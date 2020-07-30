<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\index\index.html";i:1585716400;}*/ ?>
<div class="row">

<div class="col-md-6">
    <div class="box">
        <div class="box-header">
          <h3 class="box-title">系统信息</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tbody>
                
            <tr>
              <td>OneBase版本</td>
              <td><?php echo $info['ob_version']; ?></td>
            </tr>  
            <tr>
              <td>操作系统</td>
              <td><?php echo $info['os']; ?></td>
            </tr>
            <tr>
              <td>运行环境</td>
              <td><?php echo $info['software']; ?></td>
            </tr>
            <tr>
              <td>MySql版本</td>
              <td><?php echo $info['mysql_version']; ?></td>
            </tr>
            <tr>
              <td>PHP版本</td>
              <td><?php echo $info['php_version']; ?></td>
            </tr>
            <tr>
              <td>上传限制</td>
              <td><?php echo $info['upload_max']; ?></td>
            </tr>
          </tbody>
          </table>
        </div>
      </div>
</div>



<div class="col-md-6">
    
    <div class="box">
        <div class="box-header">
          <h3 class="box-title">产品信息</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tbody>

            <tr>
              <td>产品名称</td>
              <td><?php echo $info['product_name']; ?></td>
            </tr> 
            <tr>
              <td>产品设计</td>
              <td><?php echo $info['author']; ?></td>
            </tr>
            <tr>
              <td>技术支持</td>
              <td><?php echo $info['mobile']; ?></td>
            </tr>
            <tr>
              <td>官方网址</td>
              <td><a target="_blank" href="http://<?php echo $info['website']; ?>"><?php echo $info['website']; ?></a></td>
            </tr>
            <tr>
              <td>开发手册</td>
              <td><?php echo $info['document']; ?></td>
            </tr>
            <tr>
              <td>QQ交流群</td>
              <td><?php echo $info['qun']; ?></td>
            </tr>
          </tbody>
          </table>
        </div>
      </div>
</div>
  
  </div>

