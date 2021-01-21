<?php
require_once("../config.php");
$pagecount=999;
echo $api->head("开心一笑");

//http://haha.sogou.com/port/getNextJoke?jid=2710061
$id=isset($_GET['id'])?$_GET['id']:"2710061";

if ($mobile){$style="height:230px;";}else{$style="width:800px;height:470px;";}
$html= <<<api
		<ul class="breadcrumb">
			<li><a href="/">首页</a></li>
			<li><a href="./">小视频首页</a></li>
		</ul>
	<li class="list-group-item">
		<center>
		<h3 id="title"></h3><hr>
		<div id="video" style="{$style}"></div>
		<hr>
			<input id="Btn1" class="btn btn-default btn-lg btn-block" type="hidden" value=" 上一个 " name="" data-loading-text="Loading..."/>
			<input id="Btn2" class="btn btn-default btn-lg btn-block" type="button" value=" 下一个 " name="" data-loading-text="Loading..."/>
		<hr>
			<a href="javascript:history.back(-1)" class="btn btn-default btn-lg btn-block">返回上一页</a>
		</center>
	</li>

<script type="text/javascript" src="../class/ckplayer/ckplayer.js"></script>
<script>
function reurl(title,url){
				stateObject = {};
				history.pushState(stateObject,title,url);
}
function addvideo(num,type,title,img,content){
	console.log(type+'\\n'+title+'\\n'+img+'\\n'+content)
		$('title').html(title)
		if (type==3){
			$('#title').html('<span class="glyphicon glyphicon-play-circle"><span>'+title)
			var videoObject = {
				container: '#video', //容器的ID或className
				variable: 'player',//播放函数名称
				//flashplayer:true, //强制flash播放器
				autoplay: false, //自动播放
				loop:false,
				poster:img,//封面图片
				video: [//视频地址列表形式
					[content, 'video/mp4', 'mp4', 1],
				]
			};
			var player = new ckplayer(videoObject);
		}
		if (type==2){
			$('#title').html('<span class="glyphicon glyphicon-picture"><span>'+title)
			$('#video').attr("style","")
			$('#video').html("<img src='"+img+"' style='max-width:100%'>")
		}
		if (type==1){
			$('#title').html('<span class="glyphicon glyphicon-edit"><span>'+title)
			$('#video').attr("style","")
			$('#video').html(content)
		}
}
(function($){
$.getUrlParam = function(name){
var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
var r = window.location.search.substr(1).match(reg);
if (r!=null) return unescape(r[2]); return null;
}})(jQuery);
num=$.getUrlParam('num')
num=num?num:0
;!function(){
	$.ajax({
	url:'//haha.sogou.com/port/getNextJoke?jid={$id}',
		type:'get',
		timeout:'15000',
		async:false,
		dataType:'jsonp',
		jsonpCallback:'a',
		success:function(data){
			window.datas=data.list
			addvideo(num,datas[num]['type'],datas[num]['title'],datas[num]['image_url'],datas[num]['text'])
		}
	});
	$('#Btn2').click(function (){
            $(this).button('loading').delay(800).queue(function() {
            $(this).button('reset');
            $(this).dequeue(); 
			});
		num++;
		console.log(num)
		if (num>0){
			$('#Btn1').attr("type","button")
		}
		if(num<datas.length){
			reurl(datas[num]['title'],"index1.php?id={$id}&num="+num)
			addvideo(num,datas[num]['type'],datas[num]['title'],datas[num]['image_url'],datas[num]['text'])
		}else{
			num=datas.length-1
			layer.msg("列表已完毕，请刷新获取新内容", {time: 3000,anim:6})
		}
	})
	$('#Btn1').click(function (){
            $(this).button('loading').delay(800).queue(function() {
            $(this).button('reset');
            $(this).dequeue(); 
			});
		num--;
		console.log(num)
		if(num>=0){
			reurl(datas[num]['title'],"index1.php?id={$id}&num="+num)
			addvideo(num,datas[num]['type'],datas[num]['title'],datas[num]['image_url'],datas[num]['text'])
		}else{
			num=0
			layer.msg("已经到头了", {time: 3000,anim:6})
		}
	})
	console.log(num)
}();
</script>
api;



$html.=$api->end();
echo $html;