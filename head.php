<?php
$html_head='<!DOCTYPE html>
<html>
<head>
	<title>'.$web_title.'</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
	<meta http-equiv="Cache-Control" content="max-age=0"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=0">
    <meta name="referrer" content="no-referrer">
	<link rel="stylesheet" href="//cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="//cdn.static.runoob.com/libs/jquery/1.12.3/jquery.min.js"></script>
	<script src="/class/jquery.cookie.js"></script>
	<script src="//cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="/class/layer/layer.js"></script>
	<script src="/class/my.js"></script>
	<style>
		.container{max-width:950px;}
		.modal-body img{max-width:100%;}
		.gopage input {
			height: 100%;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 4em;
		}
	</style>
</head>
<body>
<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
          <a class="navbar-brand" href="/"><span class="glyphicon glyphicon-home"></span>Home</a>
		</div>
        <div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="/haha"><span class="glyphicon glyphicon-play-circle"></span>小视频</a></li>
				<li><a href="/dwz"><span class="glyphicon glyphicon-book"></span>文章</a></li>
			</ul>
			<ul class="nav navbar-nav">
				<li class="dropdown"><a href="#" class="dropdown-toggle" id="jsontools" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-gift"></span>其它!</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<!--li class="active"><a>测试</a></li-->
						<li><a href="/duanxin"><span class="glyphicon glyphicon-book"></span>短信大全</a></li>
						<li><a href="/xhy"><span class="glyphicon glyphicon-book"></span>歇后语</a></li>
						<li><a href="/miyu2"><span class="glyphicon glyphicon-book"></span>谜语大全</a></li>
						<li><a href="/qqhead"><span class="glyphicon glyphicon-hand-up"></span>QQhead</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown"><a href="#" class="dropdown-toggle" id="jsontools" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-gift"></span>其它</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<li><a href="/baike/"><span class="glyphicon glyphicon-list-alt"></span>百科问答</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<div class="container container-small">
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">模态框标题</h4>
				</div>
				<div class="modal-body">在这里添加一些文本</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<!--button type="button" class="btn btn-primary"></button-->
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
';