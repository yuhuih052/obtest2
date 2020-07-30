<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"D:\phpstudy_pro\WWW\obtest\addon\region\view\index\index.html";i:1593675585;}*/ ?>
<link rel="stylesheet" href="__STATIC__/region_style.css">
<div>
    <select name="province_<?php echo $addons_data['name']; ?>" id="province_<?php echo $addons_data['name']; ?>" class="form-control addon-form-group-select"></select>
    <select name="city_<?php echo $addons_data['name']; ?>" id="city_<?php echo $addons_data['name']; ?>" class="form-control addon-form-group-select"></select>
    <select name="county_<?php echo $addons_data['name']; ?>" id="county_<?php echo $addons_data['name']; ?>" class="form-control addon-form-group-select"></select>
</div>

<script type="text/javascript">

$(function(){
    
    var province_id = "<?php echo $addons_data['province']; ?>";
    var city_id = "<?php echo $addons_data['city']; ?>";
    var county_id = "<?php echo $addons_data['county']; ?>";
    
    var get_options_url = '<?php echo addons_url("region://Index/getOptions"); ?>';
    
    function changeProvince(province_id = 0, select_id = 0)
    {
        $.get(get_options_url, {upid: province_id, select_id: select_id, level : 1}, function(result){ $("#province_<?php echo $addons_data['name']; ?>").html(result.data); });
    }

    function changeCity(city_id = 0, select_id = 0)
    {
        $.get(get_options_url, {upid: city_id, select_id: select_id, level : 2}, function(result){ $("#city_<?php echo $addons_data['name']; ?>").html(result.data); });
    }

    function changeCounty(county_id = 0, select_id = 0)
    {
        $.get(get_options_url, {upid: county_id, select_id: select_id, level : 3}, function(result){ $("#county_<?php echo $addons_data['name']; ?>").html(result.data); });
    }
    
    changeProvince(0, province_id);
    changeCity(province_id, city_id);
    changeCounty(city_id, county_id);

    $("#province_<?php echo $addons_data['name']; ?>").change(function(){ changeCity($("#province_<?php echo $addons_data['name']; ?>").val());});
    $("#city_<?php echo $addons_data['name']; ?>").change(function(){ changeCounty($("#city_<?php echo $addons_data['name']; ?>").val());});
});
</script>