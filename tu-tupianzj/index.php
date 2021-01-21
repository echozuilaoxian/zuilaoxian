<?php
require_once("../config.php");
use QL\QueryList;

$type=array(
	array("list"=>"xiezhen","id"=>179,"name"=>"清纯美女"),
	array("list"=>"xinggan","id"=>176,"name"=>"性感美女"),
	array("list"=>"guzhuang","id"=>177,"name"=>"古装美女"),
	array("list"=>"yishu","id"=>178,"name"=>"人体艺术"),
	array("list"=>"siwa","id"=>193,"name"=>"丝袜美女"),
	array("list"=>"chemo","id"=>194,"name"=>"香车美人")
);
$list=$_GET['list']??'xiezhen';
$listid=$_GET['listid']??NULL;
if (!$listid){
	$url="https://www.tupianzj.com/meinv/{$list}/";
}else{
	$url="https://www.tupianzj.com/meinv/{$list}/list_{$listid}_{$page}.html";
}
$datahtml = $api->GetHtml($url,$huan_path,120);
/*获取列表*/
$datahtml2=$api->cutstr($datahtml,'list_con_box_ul','<\/ul>');
$datahtml2=iconv("GB2312","UTF-8//IGNORE",$datahtml2);
$pattern='/<li>[\w\W]*?href="(?<id>[\w\W]*?)"[\w\W]*?(title|alt)="(?<title>[\w\W]*?)"[\w\W]*?src="(?<img>[\w\W]*?)"[\w\W]*?tpinfo/i';
preg_match_all($pattern, $datahtml2, $matches);
unset($matches[0]);
$data=$matches;
/*
$rules=array(
	"img"=>array('img','src'),
	"id"=>array('','href'),
	"title"=>array('','title')
);
$range='.list_con_box_ul>li>a';
$data = QueryList::html($datahtml)
->rules($rules)
->range($range)
->encoding('UTF-8')
->removeHead()
->queryData();
*/

/*获取总页数*/
$page_count=$api->cutstr($datahtml,"<strong>","<\/strong>");
$listid=$api->cutstr($datahtml,'<div class="pages">[\w\W]*?href=("|\')','("|\')',2);
$listid=explode('_',$listid)[1];
$type_h="<ul class=\"breadcrumb\">\n";
foreach ($type as $index){
	if (array_search($listid,$index)){
		$type_h.="<li><a href=\"?list={$index['list']}\"><font color=\"red\">{$index['name']}</font></a></li>\n";
		$title=$index['name'];
	}else{
		$type_h.="<li><a href=\"?list={$index['list']}\">{$index['name']}</a></li>\n";
	}
}
$type_h.="</ul>\n";

$title=$title??"漂亮美眉";
echo $api->head($title);
echo $type_h;

$gopage="?list={$list}&listid={$listid}&";
echo $api->api_page($page_count,$page,$gopage);
	for($i=0;$i<count($data['id']);$i++){
		$id=base64_encode($data["id"][$i]);
		$pic=$data["img"][$i];
		$title=$data["title"][$i];
echo <<<api

	<div class="media">
		<div class="media-left media-middle">
			<img src="{$pic}" class="media-object" style="width:120px">
		</div>
		<div class="media-body">
			<a href="view.php?id={$id}">
				<h4 class="media-heading">{$title}</h4>
			</a>
		</div>
	</div>
	
api;
}

echo $api->api_page($page_count,$page,$gopage);
echo $api->end();