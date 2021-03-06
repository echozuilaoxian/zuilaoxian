<?php
require_once("config.php");
echo $api->head("小应用");
echo <<<api
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		<small>本站运行环境php7,Composer(php-curl-class,querylist<4.1.1)<br/>大部分功能页面可以通过在网址后添加参数web_charset=json访问json数据</small>
	</div>
	<style>
		.panel-body{
			width: 100%;
			padding:10px 10px;
		}
		.panel-body li{
			display: inline-block;
			margin: 0 auto;
			width: 49%;
			text-align: center;
		}
		.panel-body li a{
			display: inline-block;
			width: 80%;
			color: #b131b9;
			text-align: center;
			padding: 6px 0;
			box-shadow: inset 0px 0px 5px #ea88f1;
			margin: 9px -1px;
			border-radius: 4px;
		}
		.panel-body li a:hover{
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
			<div class="panel-body">
				<li><a href="/dwz/">美文 <span class="glyphicon glyphicon-book"><span></a></li>
				<li><a href="/rizhi/">日志</a></li>
				<li><a href="/lizhi/">励志</a></li>
				<li><a href="/ggs/">鬼故事</a></li>
				<li><a href="/lssdjt/">历史上的今天</a></li>
				<li><a href="/xs1/">小说阅读①</a></li>
			</div>
			<!--div class="panel-footer"></div-->
			<div id="collapse1" class="list-group panel-collapse collapse in">
			</div>
		</div>

		<div class="panel panel-info">
			<div class="panel-heading">
				<span data-toggle="collapse" data-parent="#accordion" href="#collapse2">
					<div class="panel-title"><small><span class="glyphicon glyphicon-play-circle"><span></small> 多媒体 <span class="badge">8</span></div>
				</span>
			</div>
			<div class="panel-body">
				<li><a href="/haha">小视频</a></li>
				<li><a href="/qqhead">QQ头像获取</a></li>
				<li><a href="/enterdesk">漂亮美眉3</a></li>
				<li><a href="/tu-tupianzj">美女图片</a></li>
				<li><a href="/wallpaper">壁纸大全</a></li>
				<li><a href="/m">音乐搜索</a></li>
				<li><a href="/douyin">抖音解析</a></li>
				<li><a href="/jianya">减压神器</a></li>
			</div>
			<div id="collapse2" class="list-group panel-collapse collapse in">
			</div>
		</div>

		<div class="panel panel-warning">
			<div class="panel-heading">
				<span data-toggle="collapse" data-parent="#accordion" href="#collapse3">
					<div class="panel-title"><small><span class="glyphicon glyphicon-gift"><span></small> 其他 <span class="badge">13</span></div>
				</span>
			</div>
			<div class="panel-body">
				<li><a href="/duanxin">短信大全</a></li>
				<li><a href="/xhy">歇后语</a></li>
				<li><a href="/miyu">谜语大全</a></li>
				<li><a href="/miyu2">谜语大全2</a></li>
				<li><a href="/naojin">脑筋急转弯</a></li>
				<li><a href="/chengyu">成语大全</a></li>
				<li><a href="/pianfang">民间偏方</a></li>
				<li><a href="/baike">百科问答</a></li>
				<li><a href="/mymj">名言名句</a></li>
				<li><a href="/raokouling">绕口令</a></li>
				<li><a href="/yanyu">谚语</a></li>
				<li><a href="/shengjing">圣经</a></li>
				<li><a href="/zhanan">渣男绿茶语录</a></li>
			</div>
			<div id="collapse3" class="list-group panel-collapse collapse in">
			</div>
		</div>

		<div class="panel panel-danger">
			<div class="panel-heading">
				<span data-toggle="collapse" data-parent="#accordion" href="#collapse4">
					<div class="panel-title"><small><span class="glyphicon glyphicon-th-list"><span></small> 功能类 <span class="badge">6</span></div>
				</span>
			</div>
			<div class="panel-body">
				<li><a href="/ip">IP地址查询 <span class="glyphicon glyphicon-globe"><span></a></li>
				<li><a href="/post">全国邮编查询 <span class="glyphicon glyphicon-envelope"><span></a></li>
				<li><a href="/ewm">二维码制作 <span class="glyphicon glyphicon-qrcode"><span></a></li>
				<li><a href="/wtp">文字转图片</a></li>
				<li><a href="/kouzhao">给头像加个口罩</a></li>
				<li><a href="/doutu">斗图表情大全装逼生成</a></li>
			</div>
			<div id="collapse4" class="list-group panel-collapse collapse in">
			</div>
		</div>
		
	</div>

	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		本站之资源来源于网络收集，若侵犯了您的权益，请邮件联系：zuilaoxian@qq.com
	</div>

api;
echo $api->end();