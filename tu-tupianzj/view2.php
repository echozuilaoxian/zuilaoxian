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
<link rel="stylesheet" href="swipebox.css">
<style type="text/css">
		.htmleaf-icon,.htmleaf-header h1 span{color: #fff;}
		.containerss{width: 100%;margin: 0 auto;}
		.containerss:nth-child(even){background:whitesmoke}
		.containerss:nth-child(odd){background:#fff}
		#box-container{margin:0;padding:0}
		.box{list-style-type:none;display:inline-block}
		.box:nth-child(2n+1){clear:both;margin-left:0}
		.box:nth-child(2n+0){margin-right:0}
		.box a{display:block;width:100%;height:auto}
		.box a img{width:100%;max-width:400px;height:auto;vertical-align:bottom}
</style>
<div class="htmleaf-containerss">
	<section id="exemple" class="containerss">
		<div class="wrap small-width">
			<div id="try"></div>
			<ul id="box-container">
';		
			
			foreach($data['img'] as $row){
				if ($row){
					if (stripos($row,"http://")!==0){$row="{$row}";}
					echo '
					<li class="box">
						<a href="'.$row.'" class="swipebox" title="">
							<img src="'.$row.'" alt="image">
						</a>
					</li>
					';
				}
			}
echo '
			</ul>
		</div>
	</section>
</div>
<script src="jquery.swipebox.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
			/* Basic Gallery */
			$( \'.swipebox\' ).swipebox();
			/* Video */
			$( \'.swipebox-video\' ).swipebox();
      });
	</script>
';


echo $api->end();