<?php
require_once("config.php");
echo $api->head("小应用");
echo <<<api
<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	<small>本站运行环境php70,Composer(php-curl-class,querylist4)</small>
</div>
<style>
.alist{
    display: block;
    margin: 0 auto;
	padding:5px 0;
	text-align: center;
}
.alist a{
    display: inline-block;
    width: 48%;
    color: #b131b9;
    text-align: center;
    padding: 6px 0;
    /* border: 1px solid #b131b9; */
    box-shadow: inset 0px 0px 8px #b131b9;
    margin: 9px -1px 5px 4px;
    /* border:0 #fff; */
    border-radius: 4px;
}
.alist a:hover{
	font-weight: 300;
	text-shadow: 0 0 1px #e69fea;
}
</style>
<div class="panel-group" id="accordion">
	<div class="panel panel-success">
		<div class="panel-heading">
			<span data-toggle="collapse" data-parent="#accordion" href="#collapse1">
				<div class="panel-title"><small><span class="glyphicon glyphicon-book"><span></small> 阅读类 <span class="badge">6</span></div>
			</span>
		</div>
		<!--div class="panel-body"></div-->
		<!--div class="panel-footer"></div-->
		<div id="collapse1" class="list-group panel-collapse collapse in">
		<div class="alist">
			<a href="/dwz/">美文 <span class="glyphicon glyphicon-book"><span></a>
			<a href="/rizhi/">日志</a>
			<a href="/lizhi/">励志</a>
			<a href="/ggs/">鬼故事</a>
			<a href="/lssdjt/">历史上的今天</a>
			<a href="/xs1/">小说阅读①</a>
		</div>
		</div>
	</div>

	<div class="panel panel-info">
		<div class="panel-heading">
			<span data-toggle="collapse" data-parent="#accordion" href="#collapse2">
				<div class="panel-title"><small><span class="glyphicon glyphicon-play-circle"><span></small> 多媒体 <span class="badge">8</span></div>
			</span>
		</div>
		<!--div class="panel-body"></div-->
		<div id="collapse2" class="list-group panel-collapse collapse">
		<div class="alist">
			<a href="/haha">小视频</a>
			<a href="/qqhead">QQ头像获取</a>
			<a href="/enterdesk">漂亮美眉3</a>
			<a href="/tu-tupianzj">美女图片</a>
			<a href="/wallpaper">壁纸大全</a>
			<a href="/m">音乐搜索</a>
			<a href="/douyin">抖音解析</a>
			<a href="/jianya">减压神器</a> 
		</div>
		</div>
	</div>

	<div class="panel panel-warning">
		<div class="panel-heading">
			<span data-toggle="collapse" data-parent="#accordion" href="#collapse3">
				<div class="panel-title"><small><span class="glyphicon glyphicon-gift"><span></small> 其他 <span class="badge">8</span></div>
			</span>
		</div>
		<!--div class="panel-body"></div-->
		<div id="collapse3" class="list-group panel-collapse collapse">
		<div class="alist">
			<a href="/duanxin">短信大全</a>
			<a href="/xhy">歇后语</a>
			<a href="/miyu">谜语大全</a>
			<a href="/miyu2">谜语大全2</a>
			<a href="/naojin">脑筋急转弯</a>
			<a href="/chengyu">成语大全</a>
			<a href="/pianfang">民间偏方</a>
			<a href="/baike">百科问答</a>
		</div>
		</div>
	</div>

	<div class="panel panel-danger">
		<div class="panel-heading">
			<span data-toggle="collapse" data-parent="#accordion" href="#collapse4">
				<div class="panel-title"><small><span class="glyphicon glyphicon-th-list"><span></small> 功能类 <span class="badge">4</span></div>
			</span>
		</div>
		<!--div class="panel-body"></div-->
		<div id="collapse4" class="list-group panel-collapse collapse">
		<div class="alist">
			<a href="/ewm">二维码制作 <span class="glyphicon glyphicon-qrcode"><span></a>
			<a href="/wtp">文字转图片</a>
			<a href="/kouzhao">给头像加个口罩</a>
			<a href="/doutu">斗图表情大全装逼生成</a>
		</div>
		</div>
	</div>
	
</div>

<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	本站之资源来源于网络收集，若侵犯了您的权益，请邮件联系：zuilaoxian@qq.com
</div>

api;
echo $api->end();