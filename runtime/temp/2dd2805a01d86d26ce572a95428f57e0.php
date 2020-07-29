<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\phpstudy_pro\WWW\obtest\public/../app/admin\view\widget\editor\index.html";i:1585716400;}*/ ?>
<link rel="stylesheet" href="/static/widget/admin/editor/kindeditor/default/default.css" />

<script src="/static/widget/admin/editor/kindeditor/kindeditor.js"></script>
<script src="/static/widget/admin/editor/kindeditor/zh_CN.js"></script>

<script type="text/javascript">

    
    var editor_<?php echo $widget_data['name']; ?>;
        editor_<?php echo $widget_data['name']; ?> = KindEditor.create('textarea[name="<?php echo $widget_data['name']; ?>"]', {
                allowFileManager : false,
                themesPath: KindEditor.basePath,
                width: '100%',
                height: '<?php echo $widget_config['editor_height']; ?>',
                resizeType: <?php if($widget_config['editor_resize_type'] == '1'): ?>1 <?php else: ?> 0 <?php endif; ?>,
                pasteType : 2,
                urlType : 'absolute',
                fileManagerJson : '<?php echo url('fileManagerJson'); ?>',
                uploadJson : "<?php echo url('widget/editorPictureUpload'); ?>",
                pluginsPath: static_root + 'widget/admin/editor/kindeditor/plugins/',
                items : [
                'source',       'undo',             'redo',             'cut',              'copy',             'paste',        'plainpaste',
                'wordpaste',    'selectall',        'justifyleft',      'justifycenter',    'justifyright',     'justifyfull',  'insertorderedlist',
                'indent',       'outdent',          'subscript',        'superscript',      'fontname',         'fontsize',     'forecolor',
                'hilitecolor',  'bold',             'italic',           'underline',        'strikethrough',    'removeformat', 'image',
                'table',        'link',             'unlink',           'fullscreen',       'emoticons',        'baidumap',      'preview',
                'print',        'template',         'code',             'quickformat'
                ],
                extraFileUploadParams: { session_id : '<?php echo session_id(); ?>'},
                afterBlur: function(){editor_<?php echo $widget_data['name']; ?>.sync();}
            });
</script>
