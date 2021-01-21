<?php
require "../config.php";
use QL\QueryList;

$id=$_GET['id']??NULL;
if (!$id){exit($api->msg("id错误","id错误","danger"));}
$url="https://www.tupianzj.com/".base64_decode($id);
$datahtml = $api->GetHtml($url,$huan_path,120);
$ql=QueryList::html($datahtml);
$data['title']=$ql->find('h1:last')->text();
$data['img'][]=$ql->find('#bigpic img')->src;

$datahtml=iconv("GB2312","UTF-8//IGNORE",$datahtml);
$page_m=$api->cutstr($datahtml,'<a>共','页');

/*并发请求所有图片页面*/
use \Curl\MultiCurl;
$multi_curl = new MultiCurl();
$multi_curl->success(function ($instance)use($huan_path,$data){
	$huan_file=$huan_path.'/'.base64_encode($instance->url);
	file_put_contents($huan_file,$instance->response);
});
for ($i=2;$i<=$page_m;$i++){
	$url_=str_replace('.html','_'.$i.'.html',$url);
	$huan_file=$huan_path.'/'.base64_encode($url_);
	if (!file_exists($huan_file)){$multi_curl->addGet($url_);}
}
$multi_curl->start();


for ($i=2;$i<=$page_m;$i++){
	$url_=str_replace('.html','_'.$i.'.html',$url);
	$huan_file=$huan_path.'/'.base64_encode($url_);
	$data['img'][]=(new QueryList)->html(@file_get_contents($huan_file))->find('#bigpic img')->src;
}

echo $api->head($data['title']);
echo <<<api
		<ul class="breadcrumb">
			<li><a href="/">网站首页</a></li>
			<li><a href="./">美女图片</a></li>
			<li>{$data['title']}</li>
		</ul>
api;
echo "<h3>{$data['title']}</h3>\n<hr>\n";

echo '
<div id="myCarousel" class="carousel slide">
	<!-- 轮播（Carousel）指标 -->
	<ol class="carousel-indicators">
	';
foreach($data['img'] as $i => $row){
	if ($i==0) {$c=' class="active"';}else{$c='';};
	if ($row){
		echo '
		<li data-target="#myCarousel" data-slide-to="'.$i.'"'.$c.'></li>
		';
	}
}
echo '
	</ol>   
	<!-- 轮播（Carousel）项目 -->
	<div class="carousel-inner">
	';
	foreach($data['img'] as $i => $row){
		if ($i==0) {$d=' active';}else{$d='';}
		if ($row){
			echo '
			<div class="item'.$d.'">
				<img src="'.$row.'" alt="">
			</div>
			';
		}
	}
	echo '
	</div>
	<!-- 轮播（Carousel）导航 -->
	<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div> 

';




echo $api->end();