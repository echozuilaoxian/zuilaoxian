<?php
require_once("../config.php");
$list=isset($_GET['list'])?$_GET['list']:"";
$id=isset($_GET['id'])?$_GET['id']:NULL;
$hd=isset($_GET['hd'])?$_GET['hd']:NULL;
use QL\QueryList;
use \Curl\Curl;
$curl = new Curl();

$type=array(
	array("list"=>"","name"=>"美女"),
	array("list"=>"dalumeinv","name"=>"大陆"),
	array("list"=>"rihanmeinv","name"=>"日韩"),
	array("list"=>"gangtaimeinv","name"=>"港台"),
	array("list"=>"dongmanmeinv","name"=>"动漫"),
	array("list"=>"qingchunmeinv","name"=>"清纯"),
	array("list"=>"keaimeinv","name"=>"可爱"),
	array("list"=>"oumeimeinv","name"=>"欧美")
);
$type_h="<ul class=\"breadcrumb\">\n";
foreach($type as $row){
	switch ($list) {
		case $row['list']:
			$title=$row['name'];
			$type_h.="<li>{$row['name']}</li>\n";
		break;
		default:
			$type_h.="<li><a href=\"?list={$row['list']}\">{$row['name']}</a></li>\n";
	}
}
$type_h.="</ul>\n";



if (!$id){
	
$rules=array(
	"title"=>array('img','title'),
	"id"=>array('','href'),
	"img"=>array('img','src')	
);
$range='.egeli_pic_m>.egeli_pic_li>dl>dd>a';
$huan=$huan_path."/enterdesk_list_{$list}_{$page}";
$url="https://mm.enterdesk.com/{$list}/{$page}.html";
if (!file_exists($huan) || $api->filetimes($huan,"m")>14400 || $retxt){
	$curl->download($url,$huan);
}
$datahtml=@file_get_contents($huan);

$data1 = QueryList::html($datahtml)
->rules($rules)
->range($range)
->queryData(
	function($x)use($api){
		$x['id']=$api->cutstr2($x['id'],'bizhi/','.');
		return $x;
	}
);
$rules=array(
	"pagestr"=>array('.listpages>ul>li:last a','href')
);
$data2 = QueryList::html($datahtml)
->rules($rules)
->queryData(
	function($x)use($api,$list){
		if (!$list){$list3="com/";}else{$list3="{$list}/";}
		return $api->cutstr2($x['pagestr'],"{$list3}",".");
	}
)[0];
//
echo $api->head($title);

echo $type_h;

foreach($data1 as $index){
	echo <<<api
	<div class="media">
		<div class="media-left media-middle">
			<img src="{$index['img']}" class="media-object" style="width:120px">
		</div>
		<div class="media-body">
			<a href="?id={$index['id']}">
				<h4 class="media-heading">{$index['title']}</h4>
			</a>
		</div>
	</div>
	
api;
}

echo "</div>\r\n<div class=\"container container-small\">";
echo $api->api_page($data2,$page,"?list={$list}&");
}


if ($id){
	$rules=array(
		"img"=>array('a','src')	
	);
	$range='.swiper-wrapper>.swiper-slide';
	$huan=$huan_path."/enterdesk_view_{$id}";
	$url="https://mm.enterdesk.com/bizhi/{$id}.html";
	if (!file_exists($huan) || $api->filetimes($huan,"m")>14400 || $retxt){
		$curl->download($url,$huan);
	}
	$datahtml=@file_get_contents($huan);

	$data1 = QueryList::html($datahtml)
	->rules($rules)
	->range($range)
	->queryData(
		function($x)use($hd){
			if ($hd){$x['img']=str_replace('edpic','edpic_source',$x['img']);}
			return $x;
		}
	);

	$data2=QueryList::html($datahtml)->find("title")->text();

	echo $api->head($data2);
	echo "
	<ul class=\"breadcrumb\">
		<li><a href=\"/\">网站首页</a></li>
		<li><a href=\"./\">漂亮mm</a></li>
		<li>{$data2}</li>
	</ul>
	";
	foreach($data1 as $index){
		echo "
	<img src=\"{$index["img"]}\" style=\"width:100%;max-width:400px;\">";
	}

}

echo $api->end();