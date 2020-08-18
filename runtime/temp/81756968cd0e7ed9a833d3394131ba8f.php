<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"D:\phpstudy_pro\WWW\obtest2\public/../app/admin\view\cfi\cfi_list.html";i:1597636629;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>table模块快速使用</title>
  <link rel="stylesheet" href="/static/index/layui/css/layui.css" media="all">
</head>
<body>
<table style="margin-top: 45px;" id="demo" lay-filter="test"></table>
 
<script src="/static/index/sentsin-layui-master/layui/dist/layui.js"></script>
<script>
layui.use('table', function(){
  var table = layui.table;
  //第一个实例
  table.render({
    elem: '#demo'
    ,height: 312
    ,url: "<?php echo url('cfi/sellCfi'); ?>" //数据接口
     ,parseData: function(res){ //res 即为原始返回的数据
      console.log(res);
    return {
      "code": 0, //解析接口状态
      "msg": res.message, //解析提示文本
      "count": res.total, //解析数据长度
      "data": res //解析数据列表
    };
  }
    ,page: true //开启分页
    ,cols: [[ //表头
      {field: 'out_name', title: '转出方', width:100}
      ,{field: 'money', title: '金额', width:100, sort: true}
      ,{field: 'input_name', title: '转入方', width:100} 
      ,{field: 'time', title: '时间', width: 177,sort: true}
      
    ]]
  });
  
});
</script>
</body>
</html>