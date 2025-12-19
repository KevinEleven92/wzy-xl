<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="renderer" content="Blink|webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
	<meta name="author" content="dafenportrait">
	<meta name="description" content="《为之易心理健康系统》安装部署"/>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>《为之易心理健康系统》安装部署</title>
	<?php
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "head.php";
    ?>
</head>
<body  style="max-width: 900px;margin:0 auto;">
<div class="loader"></div>
<div id="main-layout" class="easyui-layout" data-options="fit:true">
	<div data-options="region:'north',border:true,height:100">
		<h1 class="text-center" style="line-height: 90px;">《为之易心理健康系统》安装部署</h1>
	</div>
    <div data-options="region:'west',minWidth:300,title:'进度提示',collapsible:false" style="width:30%;">
    </div>
    <div data-options="region:'center',title:'当前进度'">
    </div>
</div>
<!-- 公共部分 -->
<div id="global-dialog-div" class="word-wrap" style="line-height:1.5"></div>
<script type="text/javascript">
window.mainModule = {
	step:0,
	dialog:   '#global-dialog-div',
	titles:{
		"1":"安装协议",
		"2":"环境检查",
		"3":"数据库建立与设置",
		"4":"安装结果",
	},
	goNext:function(){
		this.step++;
		let urlLeft = '<?=url('index/Install/left')?>';
		urlLeft = GLOBAL.func.addUrlParam(urlLeft, 'step', this.step);

		let urlRight = '<?=url('index/Install/right')?>';
		urlRight = GLOBAL.func.addUrlParam(urlRight, 'step', this.step);

		$('#main-layout').layout('panel', 'west').panel('refresh', urlLeft);
		$('#main-layout').layout('panel', 'center').panel('refresh', urlRight);
		$('#main-layout').layout('panel', 'center').panel('setTitle', this.titles[this.step]);
	}
};
$.parser.onComplete = function(){
	mainModule.goNext();
	$.parser.onComplete = $.noop;
};
$(window).on('load',function(){
    $('.loader').hide();
});
</script>
<?php
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "foot.php";
?>
</body>
</html>